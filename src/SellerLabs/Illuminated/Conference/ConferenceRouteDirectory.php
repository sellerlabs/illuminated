<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Conference;

use Illuminate\Routing\Router;
use SellerLabs\Illuminated\Conference\Controllers\ConferenceController;
use SellerLabs\Illuminated\Http\Factories\ResourceFactory;
use SellerLabs\Illuminated\Http\Interfaces\RouteMapper;
use SellerLabs\Nucleus\Foundation\BaseObject;

/**
 * Class ConferenceRouteDirectory.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference
 */
class ConferenceRouteDirectory extends BaseObject implements RouteMapper
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string[]
     */
    protected $middleware;

    /**
     * Construct an instance of a ConferenceRouteDirectory.
     *
     * @param string $prefix
     * @param string[] $middleware
     */
    public function __construct($prefix = '/conference', $middleware = [])
    {
        parent::__construct();

        $this->prefix = $prefix;
        $this->middleware = $middleware;
    }

    /**
     * Map routes.
     *
     * @param Router $router
     */
    public function map(Router $router)
    {
        ResourceFactory::create(ConferenceController::class)
            ->withPrefix($this->prefix)
            ->withMiddleware($this->middleware)
            ->get('/', 'anyIndex')
            ->get('/css/main.css', 'getCss')
            ->get('/{moduleName}', 'anyModule')
            ->get('/{moduleName}/{methodName}', 'anyMethod')
            ->inject($router);
    }
}
