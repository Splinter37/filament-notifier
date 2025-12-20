<?php
namespace Umun\Notifier;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Umun\Notifier\Filament\Resources\NotificationChannelResource;
use Umun\Notifier\Filament\Pages\EventChannelConfiguration;
use Umun\Notifier\Filament\Pages\NotifierDashboard;
use Umun\Notifier\Filament\Pages\NotificationSettings;
use Umun\Notifier\Filament\Resources\NotificationEventResource;
use Umun\Notifier\Filament\Resources\NotificationTemplateResource;
use Umun\Notifier\Filament\Resources\NotificationResource;
use Umun\Notifier\Filament\Widgets\NotificationAnalyticsChart;
use Umun\Notifier\Filament\Widgets\NotificationChannelPerformance;
use Umun\Notifier\Filament\Widgets\NotificationEngagementStats;
use Umun\Notifier\Filament\Widgets\NotificationStatsOverview;
use Umun\Notifier\Filament\Widgets\NotificationTimeSeriesChart;
use Umun\Notifier\Filament\Widgets\RateLimitingStatusWidget;

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
                NotificationEngagementStats::class,
                NotificationTimeSeriesChart::class,
                NotificationAnalyticsChart::class,
                NotificationChannelPerformance::class,
                RateLimitingStatusWidget::class,
            ])
            ->resources([
                NotificationChannelResource::class,
                NotificationEventResource::class,
                NotificationTemplateResource::class,
                NotificationResource::class,
            ])
            ->pages([
                NotifierDashboard::class,
                NotificationSettings::class,
                EventChannelConfiguration::class,
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
