<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\Chromabits\Support;

use Chromabits\Nucleus\Testing\TestCase;
use Illuminate\Config\Repository;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Foundation\Application;
use PDO;

/**
 * Class HelpersTestCase.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Support
 */
abstract class HelpersTestCase extends TestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Setup testing environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->createApplication();
    }

    /**
     * Create an barebones Laravel application.
     */
    protected function createApplication()
    {
        $this->app = new Application(__DIR__ . '/../../..');

        $this->app->instance('config', new Repository([]));

        $this->app['config']->set('session.driver', 'array');
        $this->app['config']->set(
            'database',
            [
                'fetch' => PDO::FETCH_CLASS,
                'default' => 'sqlite',
                'connections' => [
                    'sqlite' => [
                        'driver' => 'sqlite',
                        'database' => ':memory:',
                        'prefix' => '',
                    ],
                ],
                'migrations' => 'migrations',
            ]
        );

        $this->app['config']->set(
            'app',
            [
                'providers' => [
                    'Illuminate\Filesystem\FilesystemServiceProvider',
                    'Illuminate\Foundation\Providers\FoundationServiceProvider',
                    'Illuminate\Pipeline\PipelineServiceProvider',
                    'Illuminate\Session\SessionServiceProvider',
                    'Illuminate\View\ViewServiceProvider',
                    DatabaseServiceProvider::class,
                    MigrationServiceProvider::class,
                ],
            ]
        );

        $this->app->registerConfiguredProviders();

        $this->app->boot();
    }

    /**
     * Tear down test case.
     */
    protected function tearDown()
    {
        $this->app->flush();
    }
}
