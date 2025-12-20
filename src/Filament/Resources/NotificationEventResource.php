<?php

namespace Umun\Notifier\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Umun\Notifier\Filament\Resources\NotificationEventResource\Pages\CreateNotificationEvent;
use Umun\Notifier\Filament\Resources\NotificationEventResource\Pages\EditNotificationEvent;
use Umun\Notifier\Filament\Resources\NotificationEventResource\Pages\ListNotificationEvents;
use Umun\Notifier\Filament\Resources\NotificationEventResource\Schemas\NotificationEventForm;
use Umun\Notifier\Filament\Resources\NotificationEventResource\Tables\NotificationEventTable;
use Umun\Notifier\Models\NotificationEvent;

class NotificationEventResource extends Resource
{
    protected static ?string $model = NotificationEvent::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-bell-alert';

    protected static string|null|\UnitEnum $navigationGroup = 'Notifier';

    protected static ?int $navigationSort = 2;


    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return NotificationEventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotificationEventTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNotificationEvents::route('/'),
            'create' => CreateNotificationEvent::route('/create'),
            'edit' => EditNotificationEvent::route('/{record}/edit'),
        ];
    }
}
