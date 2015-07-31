<?php

namespace Chromabits\Illuminated\Http;

use Chromabits\Illuminated\Http\Interfaces\RouteMapper;
use Chromabits\Nucleus\Foundation\BaseObject;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;

/**
 * Class RouteAggregator
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Http
 */
class RouteAggregator extends BaseObject implements RouteMapper
{
    /**
     * List of mappers to use.
     *
     * @var array
     */
    protected $mappers = [];

    /**
     * Current application.
     *
     * @var Application
     */
    protected $app;

    /**
     * Construct an instance of a RouteAggregator.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Set mappers to use.
     *
     * @param string[] $mappers
     */
    public function setMappers($mappers)
    {
        $this->mappers = $mappers;
    }

    /**
     * Map routes.
     *
     * @param Router $router
     *
     * @return mixed
     */
    public function map(Router $router)
    {
        array_map(function ($mapperName) use ($router) {
            /** @var RouteMapper $mapper */
            $mapper = $this->app->make($mapperName);
            $mapper->map($router);
        }, $this->mappers);
    }
}
