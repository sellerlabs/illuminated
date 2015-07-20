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

use Chromabits\Nucleus\Testing\TestCase;
use Mockery;

/**
 * Class LaravelTestCase
 *
 * Base test case for all Laravel unit tests.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Testing
 */
abstract class LaravelTestCase extends TestCase
{
    use ApplicationTrait, AssertionsTrait;

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        return require HELPERS_BASE_PATH . '/bootstrap/start.php';
    }

    /**
     * Setup the testing environment
     */
    public function setUp()
    {
        if (!$this->app) {
            $this->refreshApplication();
        }

        $this->app->make('artisan')->call('migrate');
    }

    /**
     * Assert that an object has all attributes in an array.
     *
     * @param array $attributes
     * @param $object
     * @param string $message
     */
    public function assertObjectHasAttributes(
        array $attributes,
        $object,
        $message = ''
    ) {
        foreach ($attributes as $attr) {
            $this->assertObjectHasAttribute($attr, $object, $message);
        }
    }

    /**
     * Tear down tests
     */
    public function tearDown()
    {
        if ($this->app) {
            $this->app->flush();
        }

        Mockery::close();
    }
}
