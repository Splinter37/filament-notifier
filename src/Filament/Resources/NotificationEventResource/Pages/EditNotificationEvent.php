<?php
namespace Umun\Notifier\Filament\Resources\NotificationEventResource\Pages;

use Umun\Notifier\Filament\Resources\NotificationEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotificationEvent extends EditRecord
{
    protected static string $resource = NotificationEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
