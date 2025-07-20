# Filament Notifier

A powerful notification system for FilamentPHP that handles multi-channel notifications with template management, scheduling, and real-time delivery. Built for developers who need enterprise-grade notifications without the complexity.

## Features

- 🚀 **Multi-Channel Support**: Email, SMS, Slack, and more
- 📝 **Template Management**: Create and manage notification templates with variable support
- ⏰ **Scheduled Notifications**: Send notifications at specific times
- 🎯 **Event-Driven**: Trigger notifications based on application events
- 👥 **User Preferences**: Allow users to control their notification preferences
- 📊 **Analytics Dashboard**: Track notification delivery and engagement
- 🔧 **Easy Configuration**: Simple setup with comprehensive configuration options
- 🧪 **Fully Tested**: Comprehensive test suite for reliability

## Installation

### 1. Install the Package

```bash
composer require usamamuneerchaudhary/filament-notifier
```

### 2. Publish and Run Migrations

```bash
php artisan vendor:publish --provider="Usamamuneerchaudhary\Notifier\NotifierServiceProvider"
php artisan migrate
```

### 3. Register the Plugin

Add the plugin to your Filament panel configuration:

```php
use Usamamuneerchaudhary\Notifier\FilamentNotifierPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentNotifierPlugin::make(),
        ]);
}
```

### 4. Configure Channels

Update your `.env` file with your notification channel settings:

```env
# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls

# Slack Configuration
SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL

# SMS Configuration (Twilio)
TWILIO_ACCOUNT_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_PHONE_NUMBER=+1234567890
```

## Usage

### Basic Notification Sending

```php
use Usamamuneerchaudhary\Notifier\Services\NotifierManager;

// Send a notification
$notifier = app('notifier');
$notifier->send($user, 'user.registered', [
    'name' => $user->name,
    'email' => $user->email,
]);
```

### Scheduled Notifications

```php
use Carbon\Carbon;

// Schedule a notification for later
$notifier->schedule($user, 'reminder.email', Carbon::now()->addDays(7), [
    'task_name' => 'Complete project review',
]);
```

### Event-Based Notifications

Register events in your `config/notifier.php`:

```php
'events' => [
    'user.registered' => [
        'channels' => ['email', 'slack'],
        'template' => 'welcome-email',
    ],
    'order.completed' => [
        'channels' => ['email', 'sms'],
        'template' => 'order-confirmation',
    ],
],
```

### Creating Templates

Templates can be created through the Filament admin panel or programmatically:

```php
use Usamamuneerchaudhary\Notifier\Models\NotificationTemplate;

NotificationTemplate::create([
    'title' => 'Welcome Email',
    'key' => 'welcome-email',
    'type' => 'email',
    'subject' => 'Welcome to {{app_name}}, {{name}}!',
    'content' => 'Hi {{name}},\n\nWelcome to {{app_name}}! We\'re excited to have you on board.',
    'variables' => [
        'name' => 'User\'s full name',
        'app_name' => 'Application name',
    ],
]);
```

### Custom Channel Drivers

Create custom channel drivers by implementing the `ChannelDriverInterface`:

```php
use Usamamuneerchaudhary\Notifier\Services\ChannelDrivers\ChannelDriverInterface;
use Usamamuneerchaudhary\Notifier\Models\Notification;

class CustomChannelDriver implements ChannelDriverInterface
{
    public function send(Notification $notification): bool
    {
        // Your custom sending logic here
        return true;
    }

    public function validateSettings(array $settings): bool
    {
        // Validate your channel settings
        return !empty($settings['api_key'] ?? null);
    }
}
```

## Configuration

### Channel Configuration

```php
// config/notifier.php
'channels' => [
    'email' => [
        'enabled' => true,
        'driver' => 'smtp',
        'from_address' => 'noreply@example.com',
        'from_name' => 'Your App',
    ],
    'slack' => [
        'enabled' => true,
        'webhook_url' => env('SLACK_WEBHOOK_URL'),
        'channel' => '#notifications',
    ],
    'sms' => [
        'enabled' => true,
        'driver' => 'twilio',
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'phone_number' => env('TWILIO_PHONE_NUMBER'),
    ],
],
```

### Event Configuration

```php
'events' => [
    'user.registered' => [
        'channels' => ['email', 'slack'],
        'template' => 'welcome-email',
        'delay' => 0, // Send immediately
    ],
    'order.shipped' => [
        'channels' => ['email', 'sms'],
        'template' => 'order-shipped',
        'delay' => 300, // 5 minutes delay
    ],
],
```

## API Reference

### NotifierManager

#### Methods

- `send($user, string $eventKey, array $data = [])`: Send a notification
- `sendNow($user, string $eventKey, array $data = [])`: Send immediately without queuing
- `schedule($user, string $eventKey, Carbon $scheduledAt, array $data = [])`: Schedule a notification
- `registerChannel(string $type, $handler)`: Register a custom channel driver
- `registerEvent(string $key, array $config)`: Register an event configuration

### Models

#### NotificationChannel
- `title`: Channel display name
- `type`: Channel type (email, sms, slack, etc.)
- `icon`: Icon for the channel
- `is_active`: Whether the channel is active
- `settings`: Channel-specific settings

#### NotificationTemplate
- `title`: Template display name
- `key`: Unique template identifier
- `type`: Template type
- `subject`: Email subject line
- `content`: Template content with variable placeholders
- `variables`: Available variables for the template

#### Notification
- `notification_template_id`: Associated template
- `user_id`: Target user
- `channel`: Channel type
- `subject`: Rendered subject
- `content`: Rendered content
- `status`: Notification status (pending, sent, failed)
- `scheduled_at`: Scheduled send time
- `sent_at`: Actual send time

## Testing

Run the test suite:

```bash
composer test
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

## Support

For support, please open an issue on GitHub or contact us at hello@usamamuneer.me.
