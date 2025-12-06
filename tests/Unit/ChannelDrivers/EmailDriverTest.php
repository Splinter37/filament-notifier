<?php

namespace Usamamuneerchaudhary\Notifier\Tests\Unit\ChannelDrivers;

use Illuminate\Support\Facades\Mail;
use Usamamuneerchaudhary\Notifier\Models\Notification;
use Usamamuneerchaudhary\Notifier\Services\ChannelDrivers\EmailDriver;
use Usamamuneerchaudhary\Notifier\Tests\TestCase;

class EmailDriverTest extends TestCase
{
    private EmailDriver $driver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->driver = new EmailDriver();
    }


    public function test_it_can_send_email_notification()
    {
        Mail::fake();

        $user = new class {
            public $id = 1;
            public $email = 'test@example.com';
        };

        $notification = Notification::create([
            'notification_template_id' => 1,
            'user_id' => $user->id,
            'channel' => 'email',
            'subject' => 'Test Subject',
            'content' => 'Test Content',
            'status' => 'pending',
        ]);

        // Mock the user relationship
        $notification->setRelation('user', $user);

        $result = $this->driver->send($notification);

        $this->assertTrue($result);
        // Mail::raw() doesn't create a mailable, it sends a raw message
        // So we just check that the method returned true
        $this->assertTrue($result);
    }


    public function test_it_returns_false_when_user_has_no_email()
    {
        $user = new class {
            public $id = 1;
            public $email = null;
        };

        $notification = Notification::create([
            'notification_template_id' => 1,
            'user_id' => $user->id,
            'channel' => 'email',
            'subject' => 'Test Subject',
            'content' => 'Test Content',
            'status' => 'pending',
        ]);

        $notification->user = $user;

        $result = $this->driver->send($notification);

        $this->assertFalse($result);
    }


    public function test_it_returns_false_when_user_is_null()
    {
        $notification = Notification::create([
            'notification_template_id' => 1,
            'user_id' => 1,
            'channel' => 'email',
            'subject' => 'Test Subject',
            'content' => 'Test Content',
            'status' => 'pending',
        ]);

        $notification->user = null;

        $result = $this->driver->send($notification);

        $this->assertFalse($result);
    }


    public function test_it_validates_settings_correctly()
    {
        $validSettings = [
            'smtp_host' => 'smtp.mailtrap.io',
            'smtp_port' => 2525,
        ];

        $invalidSettings = [
            'smtp_port' => 2525,
        ];

        $this->assertTrue($this->driver->validateSettings($validSettings));
        $this->assertFalse($this->driver->validateSettings($invalidSettings));
    }
}
