<?php

namespace Usamamuneerchaudhary\Notifier\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use Usamamuneerchaudhary\Notifier\NotifierServiceProvider;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
            NotifierServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Setup notifier config
        $app['config']->set('notifier.channels.email.enabled', true);
        $app['config']->set('notifier.channels.slack.enabled', true);
        $app['config']->set('notifier.channels.sms.enabled', true);
    }
} 