<?php

namespace Umun\Notifier\Filament\Resources\NotificationTemplateResource\Pages;

use Umun\Notifier\Filament\Resources\NotificationTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotificationTemplates extends ListRecords
{
    protected static string $resource = NotificationTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 