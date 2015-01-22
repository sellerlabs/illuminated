<?php

namespace Chromabits\Illuminated\Alerts;

use Illuminate\Support\ServiceProvider;

/**
 * Class AlertServiceProvider
 *
 * Registers the alert manager service into the container
 *
 * @package Chromabits\Illuminated\Alerts
 */
class AlertServiceProvider extends ServiceProvider
{
    /**
     * Whether the service load should be deferred
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Chromabits\Illuminated\Contracts\Alert\AlertManager', function ($app) {
            return new AlertManager(
                $app['session.store'],
                view(config('alerts.view', 'alerts.alert'))
            );
        });
    }

    public function provides()
    {
        return [
            'Chromabits\Illuminated\Contracts\Alert\AlertManager'
        ];
    }
}
