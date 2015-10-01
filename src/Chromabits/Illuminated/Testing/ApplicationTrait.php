<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Testing;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ApplicationTrait.
 *
 * @author Laravel/Lumen
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Testing
 */
trait ApplicationTrait
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The last code returned by artisan cli.
     *
     * @var int
     */
    protected $code;

    /**
     * Refresh the application instance.
     *
     */
    protected function refreshApplication()
    {
        putenv('APP_ENV=testing');

        $this->app = $this->createApplication();
    }

    /**
     * Register an instance of an object in the container.
     *
     * @param string $abstract
     * @param object $instance
     * @return object
     */
    protected function instance($abstract, $instance)
    {
        $this->app->instance($abstract, $instance);

        return $instance;
    }

    /**
     * Set the session to the given array.
     *
     * @param  array $data
     *
     */
    public function session(array $data)
    {
        $this->startSession();
        foreach ($data as $key => $value) {
            $this->app['session']->put($key, $value);
        }
    }

    /**
     * Flush all of the current session data.
     *
     */
    public function flushSession()
    {
        $this->startSession();
        $this->app['session']->flush();
    }

    /**
     * Start the session for the application.
     *
     */
    protected function startSession()
    {
        if (!$this->app['session']->isStarted()) {
            $this->app['session']->start();
        }
    }

    /**
     * Set the currently logged in user for the application.
     *
     * @param UserContract $user
     * @param string $driver
     *
     */
    public function be(UserContract $user, $driver = null)
    {
        $this->app['auth']->driver($driver)->setUser($user);
    }

    /**
     * Seed a given database connection.
     *
     * @param  string $class
     *
     */
    public function seed($class = 'DatabaseSeeder')
    {
        $this->artisan('db:seed', ['--class' => $class]);
    }

    /**
     * Call artisan command and return code.
     *
     * @param string $command
     * @param array $parameters
     *
     * @return int
     */
    public function artisan($command, $parameters = [])
    {
        /** @var Kernel $kernel */
        $kernel = $this->app['Illuminate\Contracts\Console\Kernel'];

        $this->code = $kernel->call($command, $parameters);

        return $this->code;
    }
}
