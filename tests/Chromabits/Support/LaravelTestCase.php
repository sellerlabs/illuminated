<?php

namespace Tests\Chromabits\Support;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Chromabits\Nucleus\Testing\TestCase;

/**
 * Class LaravelTestCase
 *
 * @package Chromabits\Tests\Support
 */
class LaravelTestCase extends TestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Setup testing environment
     */
    protected function setUp()
    {
        parent::setUp();

        $this->createApplication();
    }

    /**
     * Create an barebone Laravel application
     */
    protected function createApplication()
    {
        $this->app = new Application(__DIR__ . '/../../..');

        $this->app->instance('config', new Repository([]));

        $this->app['config']->set('app', [
            'providers' => [
                'Illuminate\Filesystem\FilesystemServiceProvider',
                'Illuminate\Foundation\Providers\FoundationServiceProvider',
                'Illuminate\Pipeline\PipelineServiceProvider',
                'Illuminate\Session\SessionServiceProvider',
                'Illuminate\View\ViewServiceProvider'
            ]
        ]);

        $this->app->registerConfiguredProviders();

        $this->app->boot();
    }

    /**
     * Tear down test case
     */
    protected function tearDown()
    {
        $this->app->flush();
    }
}
