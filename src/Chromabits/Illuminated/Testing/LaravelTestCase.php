<?php

namespace Chromabits\Illuminated\Testing;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery;

/**
 * Class LaravelTestCase
 *
 * Base test case for all Laravel unit tests.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Testing
 */
abstract class LaravelTestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        return require SAGE_BASE_PATH . '/bootstrap/start.php';
    }

    /**
     * Setup the testing environment
     */
    public function setUp()
    {
        BaseTestCase::setUp();

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
        BaseTestCase::tearDown();

        Mockery::close();
    }
}
