<?php

namespace NotificationChannels\CustomSms;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use CustomSms\ApiClient\Client;
use CustomSms\ApiClient\ClientFactory;

class CustomSmsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->app->when(CustomSmsChannel::class)
            ->needs(Client::class)
            ->give(function ($app) {
                if (empty($app['config']['services.customsms.token_id'])
                    || empty($app['config']['services.customsms.access_token'])) {
                    throw new \InvalidArgumentException('Missing CustomSMS config in services');
                }

                return ClientFactory::create(
                    $app['config']['services.customsms.access_token'],
                    $app['config']['services.customsms.token_id']
                );
            });
    }

    public function provides(): array
    {
        return [Client::class];
    }
}
