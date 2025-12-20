<?php

namespace Umun\Notifier\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Umun\Notifier\Filament\Resources\NotificationChannelResource\Pages\CreateNotificationChannel;
use Umun\Notifier\Filament\Resources\NotificationChannelResource\Pages\EditNotificationChannel;
use Umun\Notifier\Filament\Resources\NotificationChannelResource\Pages\ListNotificationChannels;
use Umun\Notifier\Filament\Resources\NotificationChannelResource\Schemas\NotificationChannelForm;
use Umun\Notifier\Filament\Resources\NotificationChannelResource\Tables\NotificationChannelTable;
use Umun\Notifier\Models\NotificationChannel;

class NotificationChannelResource extends Resource
{
    protected static ?string $model = NotificationChannel::class;
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-envelope';
    protected static string|null|\UnitEnum $navigationGroup = 'Notifier';
    protected static ?int $navigationSort = 3;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return NotificationChannelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotificationChannelTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNotificationChannels::route('/'),
            'create' => CreateNotificationChannel::route('/create'),
            'edit' => EditNotificationChannel::route('/{record}/edit'),
        ];
    }
}
