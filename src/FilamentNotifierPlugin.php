<?php
namespace Usamamuneerchaudhary\Notifier;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Usamamuneerchaudhary\Notifier\Filament\Resources\NotificationChannelResource;
use Usamamuneerchaudhary\Notifier\Filament\Pages\NotificationSettings;
use Usamamuneerchaudhary\Notifier\Filament\Resources\NotificationEventResource;
use Usamamuneerchaudhary\Notifier\Filament\Resources\NotificationTemplateResource;
use Usamamuneerchaudhary\Notifier\Filament\Resources\NotificationResource;
use Usamamuneerchaudhary\Notifier\Filament\Widgets\NotificationStatsOverview;

class FilamentNotifierPlugin implements Plugin
{
    public function getId(): string
    {
        return 'notifier';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->widgets([
                NotificationStatsOverview::class,
            ])
            ->resources([
                NotificationChannelResource::class,
                NotificationEventResource::class,
                NotificationTemplateResource::class,
                NotificationResource::class,
            ])
            ->pages([
                NotificationSettings::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static();
    }

}
