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

/**
 * Class ServiceMapProvider
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Support
 */
abstract class ServiceMapProvider extends ServiceProvider
{
    protected $map = [];

    protected $commands = [];

    /**
     * Get an array containing service bindings.
     *
     * Keys are expected to be abstract strings, and value scan be a string or
     * any other valid concrete type.
     *
     * @return array
     * @throws LackOfCoffeeException
     */
    protected function getServiceMap()
    {
        // Check for easy mistakes.
        if (count($this->map) == 0 && count($this->getCommands()) == 0) {
            throw new LackOfCoffeeException('Empty services map provided.');
        }

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
     * @return void
     */
    public function register()
    {
        foreach ($this->getServiceMap() as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }

        $this->commands($this->getCommands());
    }

    /**
     * Get list of services provided.
     *
     * @return array
     * @throws LackOfCoffeeException
     */
    public function provides()
    {
        return array_keys($this->getServiceMap());
    }
}
