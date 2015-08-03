<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Alerts;

use Chromabits\Illuminated\Contracts\Alerts\AlertManager as ManagerContract;
use Chromabits\Illuminated\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

/**
 * Class AlertServiceProvider.
 *
 * Registers the alert manager service into the container
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Alerts
 */
class AlertServiceProvider extends ServiceProvider
{
    /**
     * Whether the service load should be deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton(
            ManagerContract::class,
            function ($app) {
                return new AlertManager(
                    $app['session.store'],
                    view(config('alerts.view', 'alerts.alert'))
                );
            }
        );
    }

    /**
     * Register blade extension.
     */
    public function boot()
    {
        /** @var BladeCompiler $blade */
        $blade = $this->app['view']->getEngineResolver()
            ->resolve('blade')
            ->getCompiler();

        $blade->directive('allalerts', function () {
            return '<?php echo app(\'Chromabits\Illuminated\Contracts\Alerts'
            . '\AlertManager\')->allAndRender(); ?>';
        });
    }

    /**
     * Returns an array with the name of the services provided.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ManagerContract::class,
        ];
    }
}
