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

use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use SellerLabs\Illuminated\Http\Interfaces\RouteMapper;
use SellerLabs\Nucleus\Testing\TestCase;

/**
 * Class RouteMapperTestCase.
 *
 * This test case attempts to efficiently find common errors in RouteMapper
 * classes and route definitions.
 *
 * Do note that these tests make big assumptions on how the code is structured.
 * You might need to override or replace certain checks to meet your own
 * project.
 *
 * This test can be particularly slower than other since it performs some
 * introspection on controllers.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Testing
 */
abstract class RouteMapperTestCase extends TestCase
{
    protected $methods = ['get', 'post', 'delete', 'put'];

    /**
     * Build an instance of the mapper being tested.
     *
     * @return RouteMapper
     */
    abstract public function make();

    /**
     * Return a list of controllers involved in the routes mapped.
     *
     * @return string[]
     */
    abstract public function getControllers();

    public function testMap()
    {
        $router = new Router(new Dispatcher());

        $mapper = $this->make();

        $mapper->map($router);

        $routes = $router->getRoutes();
        $controllers = $this->getControllers();

        $this->assertGreaterThan(
            0,
            $routes->count(),
            'Each mapper should define at least one route.'
        );

        /** @var Route $route */
        foreach ($routes as $route) {
            $name = $route->getPath();

            // Check that it has a controller
            if (!array_key_exists('controller', $route->getAction())) {
                $this->fail(
                    'Route: ' . $name . ' has an invalid handler.'
                    . ' Only use controller handlers.'
                );
            }

            $parts = explode('@', $route->getAction()['controller']);

            // Check that it is controller@method definition
            if (count($parts) < 2) {
                $this->fail(
                    'Route: ' . $name . ' has an invalid handler: '
                    . $route->getActionName()
                    . '. Only use controller@method handlers.'
                );
            }

            // Check it begins with an HTTP method
            if (!Str::startsWith($parts[1], $this->methods)) {
                $this->fail(
                    'Route: ' . $name . ' has an invalid handler name: '
                    . $route->getActionName()
                    . '. The handler name should begin with an HTTP method.'
                );
            }

            // Make sure the controller is white-listed
            if (!in_array($parts[0], $controllers)) {
                $this->fail(
                    'Route: ' . $name . ' has an invalid controller'
                    . '. Make sure the test matches the directory controllers.'
                );
            }

            // Make sure the class method exists
            if (!method_exists($parts[0], $parts[1])) {
                $this->fail(
                    'Route: ' . $name . ' has an invalid handler.'
                    . ' Make sure the method exists.'
                );
            }
        }
    }
}
