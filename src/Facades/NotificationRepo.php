<?php

namespace Umun\Notifier\Facades;

use Illuminate\Support\Facades\Facade;
use Umun\Notifier\Services\NotificationRepository;

/**
 * @method \Umun\Notifier\Models\Notification|null findByToken(string $token)
 *
 * @see \Umun\Notifier\Services\NotificationRepository
 */
class NotificationRepo extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return NotificationRepository::class;
    }
}

