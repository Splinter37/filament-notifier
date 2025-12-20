<?php

namespace Umun\Notifier\Filament\Pages;

use Filament\Pages\Page;
use Umun\Notifier\Filament\Widgets\NotificationAnalyticsChart;
use Umun\Notifier\Filament\Widgets\NotificationChannelPerformance;
use Umun\Notifier\Filament\Widgets\NotificationEngagementStats;
use Umun\Notifier\Filament\Widgets\NotificationStatsOverview;
use Umun\Notifier\Filament\Widgets\NotificationTimeSeriesChart;
use Umun\Notifier\Filament\Widgets\RateLimitingStatusWidget;

class NotifierDashboard extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-chart-bar';
    protected static string|null|\UnitEnum $navigationGroup = 'Notifier';
    protected static ?string $title = 'Notifier Dashboard';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?int $navigationSort = 0;
    protected string $view = 'notifier::pages.dashboard';


    protected function getWidgets(): array
    {
        return [
            NotificationStatsOverview::class,
            NotificationEngagementStats::class,
            NotificationTimeSeriesChart::class,
            NotificationAnalyticsChart::class,
            NotificationChannelPerformance::class,
            RateLimitingStatusWidget::class,
        ];
    }
}

