<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Conference\Modules;

use SellerLabs\Illuminated\Conference\Controllers\HttpModuleController;
use SellerLabs\Illuminated\Conference\Module;

/**
 * Class HttpModule.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Modules
 */
class HttpModule extends Module
{
    /**
     * Construct an instance of a HttpModule.
     */
    public function __construct()
    {
        parent::__construct();

        $this->register(
            'index',
            HttpModuleController::class,
            'getIndex',
            'All Routes'
        );

        $this->register(
            'middleware',
            HttpModuleController::class,
            'getRouterMiddleware',
            'Middleware'
        );

        $this->register(
            'patterns',
            HttpModuleController::class,
            'getPatterns',
            'Patterns'
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'illuminated.conference.http';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'HTTP';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Provides information about Laravel\'s HTTP components';
    }

    /**
     * Get the name of the default method.
     *
     * @return string|null
     */
    public function getDefaultMethodName()
    {
        return 'index';
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'fa-map-o';
    }
}
