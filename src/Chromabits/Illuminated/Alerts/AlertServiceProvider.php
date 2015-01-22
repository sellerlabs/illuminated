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
    protected $defer = false;

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

    /**
     * Register blade extension
     */
    public function boot()
    {
        $this->app['blade']->extend(function ($view) {
            $alerts = app('Chromabits\Illuminated\Contracts\Alert\AlertManager')->allAndRender();

            // If there are no alerts, then we don't do anything
            if (count($alerts) < 1) {
                return;
            }

            // Combine all the array entries into a single massive string
            $content = array_reduce($alerts, function ($carry, $alert) {
                return $carry . "\n" . $alert;
            });

            return preg_replace('/(\s*)@allalerts(\s*)/', '$1' . $content . '$2', $view);
        });
    }

    /**
     * Returns an array with the name of the services provided
     *
     * @return array
     */
    public function provides()
    {
        return [
            'Chromabits\Illuminated\Contracts\Alert\AlertManager'
        ];
    }
}
