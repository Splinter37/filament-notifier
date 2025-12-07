<?php

namespace Usamamuneerchaudhary\Notifier\Services\ChannelDrivers;

use Illuminate\Support\Facades\Mail;
use Usamamuneerchaudhary\Notifier\Models\Notification;

class EmailDriver implements ChannelDriverInterface
{
    public function send(Notification $notification): bool
    {
        try {
            $channel = \Usamamuneerchaudhary\Notifier\Models\NotificationChannel::where('type', 'email')->first();
            $user = $notification->user;

            if (!$user || !$user->email) {
                return false;
            }

            $settings = $channel->settings ?? [];
            $fromAddress = $settings['from_address'] ?? config('mail.from.address', 'noreply@example.com');
            $fromName = $settings['from_name'] ?? config('mail.from.name', 'Notification');

            Mail::raw($notification->content, function (\Illuminate\Mail\Message $message) use ($notification, $user, $fromAddress, $fromName) {
                $message->to($user->email)
                        ->subject($notification->subject)
                        ->from($fromAddress, $fromName);
            });

            return true;
        } catch (\Exception $e) {
            \Log::error("Email notification failed: " . $e->getMessage());
            return false;
        }
    }

    public function validateSettings(array $settings): bool
    {
        return !empty($settings['from_address'] ?? null);
    }
}
