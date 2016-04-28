<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Http;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use SellerLabs\Illuminated\Http\Interfaces\RouteMapper;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Support\Std;

/**
 * Class RouteAggregator.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Http
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
        parent::__construct();

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
     * @return RouteMapper[]
     */
    public function getMappers()
    {
        return $this->mappers;
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
        Std::reduce(function (Router $router, $mapperName) {
            if ($mapperName instanceof RouteMapper) {
                $mapperName->map($router);

                return $router;
            }

            /** @var RouteMapper $mapper */
            $mapper = $this->app->make($mapperName);
            $mapper->map($router);

            return $router;
        }, $router, $this->getMappers());
    }

    /**
     * Get instances of the defined mappers.
     *
     * @return RouteMapper[]
     */
    public function getMapperInstances()
    {
        return Std::reduce(function (array $mappers, $mapperName) {
            if ($mapperName instanceof RouteMapper) {
                return array_merge($mappers, [$mapperName]);
            }

            /** @var RouteMapper $mapper */
            $mapper = $this->app->make($mapperName);

            return array_merge($mappers, [$mapper]);
        }, [], $this->getMappers());
    }
}
