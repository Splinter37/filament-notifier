<?php

namespace Usamamuneerchaudhary\Notifier\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Usamamuneerchaudhary\Notifier\Models\Notification;
use Usamamuneerchaudhary\Notifier\Services\ChannelDrivers\EmailDriver;
use Usamamuneerchaudhary\Notifier\Services\ChannelDrivers\SlackDriver;
use Usamamuneerchaudhary\Notifier\Services\ChannelDrivers\SmsDriver;
use Usamamuneerchaudhary\Notifier\Services\ChannelDrivers\PushDriver;
use Usamamuneerchaudhary\Notifier\Services\ChannelDrivers\DiscordDriver;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 30;

    public function __construct(
        protected int $notificationId
    ) {}

    public function handle(): void
    {
        $notification = Notification::find($this->notificationId);

        if (!$notification) {
            Log::error("Notification not found: {$this->notificationId}");
            return;
        }

        if ($notification->status === 'sent') {
            return;
        }

        try {
            $driver = $this->getDriver($notification->channel);

            if (!$driver) {
                $this->markAsFailed($notification, "No driver found for channel: {$notification->channel}");
                return;
            }

            $result = $driver->send($notification);

            if ($result) {
                $this->markAsSent($notification);
            } else {
                $this->markAsFailed($notification, "Driver failed to send notification");
            }
        } catch (\Exception $e) {
            $this->markAsFailed($notification, $e->getMessage());
            throw $e; // rethrow to trigger retry
        }
    }

    protected function getDriver(string $channelType)
    {
        return match ($channelType) {
            'email' => new EmailDriver(),
            'slack' => new SlackDriver(),
            'sms' => new SmsDriver(),
            'push' => new PushDriver(),
            'discord' => new DiscordDriver(),
            default => null,
        };
    }

    protected function markAsSent(Notification $notification): void
    {
        $notification->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    protected function markAsFailed(Notification $notification, string $error): void
    {
        $notification->update([
            'status' => 'failed',
            'error' => $error,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        $notification = Notification::find($this->notificationId);

        if ($notification) {
            $this->markAsFailed($notification, $exception->getMessage());
        }
    }
}
