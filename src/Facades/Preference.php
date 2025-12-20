<?php

namespace Umun\Notifier\Facades;

use Illuminate\Support\Facades\Facade;
use Umun\Notifier\Services\PreferenceService;

/**
 * @method static array getUserPreferences($user, string $eventKey)
 * @method static array getChannelsForEvent(\Umun\Notifier\Models\NotificationEvent $event, ?\Umun\Notifier\Models\NotificationPreference $preference)
 * @method static bool shouldSendToChannel($user, string $channelType, array $preferences)
 *
 * @see \Umun\Notifier\Services\PreferenceService
 */
class Preference extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return PreferenceService::class;
    }
}

