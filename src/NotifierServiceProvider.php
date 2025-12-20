<?php

namespace Umun\Notifier;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Umun\Notifier\Commands\CleanupAnalyticsCommand;
use Umun\Notifier\Commands\NotifierInstallCommand;
use Umun\Notifier\Commands\SendTestNotificationCommand;
use Umun\Notifier\Http\Controllers\NotificationPreferenceController;
use Umun\Notifier\Http\Controllers\NotificationTrackingController;
use Umun\Notifier\Models\Notification;
use Umun\Notifier\Models\NotificationChannel;
use Umun\Notifier\Models\NotificationEvent;
use Umun\Notifier\Models\NotificationPreference;
use Umun\Notifier\Models\NotificationTemplate;
use Umun\Notifier\Services\AnalyticsService;
use Umun\Notifier\Services\ChannelDriverFactory;
use Umun\Notifier\Services\NotificationRepository;
use Umun\Notifier\Services\NotifierManager;
use Umun\Notifier\Services\PreferenceService;
use Umun\Notifier\Services\UrlTrackingService;

class NotifierServiceProvider extends PackageServiceProvider
{
    public static string $name = 'notifier';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations()
            ->hasCommands([
                NotifierInstallCommand::class,
                SendTestNotificationCommand::class,
                CleanupAnalyticsCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('notifier', function () {
            return new NotifierManager();
        });

        $this->app->bind('notifier.channel', NotificationChannel::class);
        $this->app->bind('notifier.event', NotificationEvent::class);
        $this->app->bind('notifier.template', NotificationTemplate::class);
        $this->app->bind('notifier.preference', NotificationPreference::class);
        $this->app->bind('notifier.notification', Notification::class);

        $this->app->singleton(PreferenceService::class);
        $this->app->singleton(AnalyticsService::class);
        $this->app->singleton(UrlTrackingService::class);
        $this->app->singleton(NotificationRepository::class);
    }

    public function packageBooted(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'notifier');

        $this->registerChannelsFromDatabase();
        $this->registerApiRoutes();
        $this->registerTrackingRoutes();
    }

    /**
     * Register API routes for user preferences
     */
    protected function registerApiRoutes(): void
    {
        Route::prefix('api/notifier/preferences')->group(function () {
            Route::get('/', [NotificationPreferenceController::class, 'index']);
            Route::get('/available', [NotificationPreferenceController::class, 'available']);
            Route::get('/{eventKey}', [NotificationPreferenceController::class, 'show']);
            Route::put('/{eventKey}', [NotificationPreferenceController::class, 'update']);
        });
    }

    /**
     * Register tracking routes for analytics ---- public routes
     */
    protected function registerTrackingRoutes(): void
    {
        Route::prefix('notifier/track')->group(function () {
            Route::get('/open/{token}', [NotificationTrackingController::class, 'trackOpen']);
            Route::get('/click/{token}', [NotificationTrackingController::class, 'trackClick']);
        });
    }

    protected function registerChannelsFromDatabase(): void
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('notifier_channels')) {
                return;
            }

            $notifier = $this->app->make('notifier');
            $channels = \Umun\Notifier\Models\NotificationChannel::where('is_active', true)->get();

            $driverFactory = new ChannelDriverFactory();
            foreach ($channels as $channel) {
                $driver = $driverFactory->create($channel->type);
                if ($driver) {
                    $notifier->registerChannel($channel->type, $driver);
                }
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            // Silently fail
        }
    }

}
