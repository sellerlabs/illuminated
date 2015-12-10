<?php

namespace Chromabits\Illuminated\Http;

use Chromabits\Illuminated\Http\Factories\ResourceFactory;
use Chromabits\Illuminated\Http\Interfaces\RouteMapper;
use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Support\Std;
use Illuminate\Routing\Router;

/**
 * Class ResourceAggregator.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Http
 */
abstract class ResourceAggregator extends BaseObject implements RouteMapper
{
    /**
     * Get resources to be aggregated.
     *
     * @return ResourceFactory[]
     */
    abstract public function getResources();

    /**
     * Map all the routes contained in every resource factory.
     *
     * @param Router $router
     *
     * @return Router
     */
    public function map(Router $router)
    {
        return Std::reduce(
            function (Router $router, ResourceFactory $factory) {
                return $factory->inject($router);
            },
            $router,
            $this->getResources()
        );
    }
}