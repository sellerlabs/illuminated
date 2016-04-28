<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Testing;

use Mockery;
use SellerLabs\Nucleus\Testing\TestCase;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class LaravelTestCase.
 *
 * Base test case for all Laravel unit tests.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Testing
 */
abstract class FrameworkTestCase extends TestCase
{
    use ApplicationTrait, AssertionsTrait, CrawlerTrait;

    /**
     * The callbacks that should be run before the application is destroyed.
     *
     * @var array
     */
    protected $beforeDestroyCallbacks = [];

    /**
     * Creates the application.
     *
     * @return HttpKernelInterface
     */
    abstract public function createApplication();

    /**
     * Setup the testing environment.
     */
    public function setUp()
    {
        if (!$this->app) {
            $this->refreshApplication();
        }
    }

    /**
     * Tear down tests.
     */
    public function tearDown()
    {
        if (class_exists('Mockery')) {
            Mockery::close();
        }

        if ($this->app) {
            foreach ($this->beforeDestroyCallbacks as $callback) {
                call_user_func($callback);
            }

            $this->app->flush();

            $this->app = null;
        }

        if (property_exists($this, 'serverVariables')) {
            $this->serverVariables = [];
        }
    }

    /**
     * Register a callback to be run before the application is destroyed.
     *
     * @param callable $callback
     */
    protected function beforeDestroyingApplication(callable $callback)
    {
        $this->beforeDestroyCallbacks[] = $callback;
    }
}
