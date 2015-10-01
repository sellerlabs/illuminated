<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Support;

use Chromabits\Nucleus\Exceptions\LackOfCoffeeException;
use Chromabits\Nucleus\Support\Arr;
use Chromabits\Nucleus\Support\Std;

/**
 * Class ServiceMapProvider.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Support
 */
abstract class ServiceMapProvider extends ServiceProvider
{
    protected $map = [];

    protected $singletons = [];

    protected $commands = [];

    /**
     * Get an array containing service bindings.
     *
     * Keys are expected to be abstract strings, and value scan be a string or
     * any other valid concrete type.
     *
     * @throws LackOfCoffeeException
     * @return array
     */
    protected function getServiceMap()
    {
        return $this->map;
    }

    /**
     * Get an array of commands this provider should register.
     *
     * @return array
     */
    protected function getCommands()
    {
        return $this->commands;
    }

    /**
     * Register the service provider.
     *
     */
    public function register()
    {
        foreach ($this->getServiceMap() as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }

        foreach ($this->getSingletons() as $abstract => $concrete) {
            $this->app->singleton($abstract, $concrete);
        }

        $this->commands($this->getCommands());
    }

    /**
     * Get list of services provided.
     *
     * @throws LackOfCoffeeException
     * @return array
     */
    public function provides()
    {
        return Std::concat(
            Arr::keys($this->getServiceMap()),
            Arr::keys($this->getSingletons())
        );
    }

    /**
     * @return array
     */
    public function getSingletons()
    {
        return $this->singletons;
    }
}
