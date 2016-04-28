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

use Illuminate\Routing\Router;
use SellerLabs\Illuminated\Http\Factories\ResourceFactory;
use SellerLabs\Illuminated\Http\Interfaces\RouteMapper;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Support\Std;

/**
 * Class ResourceAggregator.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Http
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
