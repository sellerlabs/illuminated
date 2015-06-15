<?php

namespace Chromabits\Illuminated\Testing;

use Chromabits\Nucleus\Support\PrimitiveType;
use Chromabits\Nucleus\Testing\TestCase;
use Illuminate\Foundation\Application;

/**
 * Class ServiceProviderTestCase
 *
 * A base class for tests testing service providers.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Testing
 */
abstract class ServiceProviderTestCase extends TestCase
{
    /**
     * List of abstracts that should be bound
     *
     * @var array
     */
    protected $shouldBeBound = [];

    /**
     * Construct an instance of a ServiceProviderTestCase
     *
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->mockApp = new Application();
    }

    /**
     * Make an instance of the service provider being tested
     *
     * @param Application $app
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    abstract public function make(Application $app);

    public function testRegister()
    {
        $instance = $this->make($this->mockApp);

        $instance->register();

        foreach ($this->shouldBeBound as $abstract) {
            $this->assertTrue(
                $this->mockApp->bound($abstract)
            );
        }
    }

    public function testProvides()
    {
        $instance = $this->make($this->mockApp);

        $result = $instance->provides();

        $this->assertInternalType(PrimitiveType::COLLECTION, $result);
    }
}
