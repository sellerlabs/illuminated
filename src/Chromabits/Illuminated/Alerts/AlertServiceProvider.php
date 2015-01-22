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
        $this->app->singleton('Chromabits\Illuminated\Contracts\Alerts\AlertManager', function ($app) {
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
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        $blade->extend(function ($view, $compiler) {
            $pattern = $compiler->createPlainMatcher('allalerts');

            return preg_replace(
                $pattern,
                '$1<?php echo app(\'Chromabits\Illuminated\Contracts\Alerts\AlertManager\')->allAndRender(); ?>$2',
                $view
            );
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
            'Chromabits\Illuminated\Contracts\Alerts\AlertManager'
        ];
    }
}
