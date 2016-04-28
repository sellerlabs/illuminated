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

use SellerLabs\Illuminated\Conference\Controllers\FrontModuleController;
use SellerLabs\Illuminated\Conference\Module;

/**
 * Class FrontModule.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Modules
 */
class FrontModule extends Module
{
    /**
     * Construct an instance of a FrontModule.
     */
    public function __construct()
    {
        parent::__construct();

        $this->register(
            'index',
            FrontModuleController::class,
            'getIndex',
            'Home'
        );

        $this->register(
            'modules',
            FrontModuleController::class,
            'getModules',
            'Modules'
        );

        $this->register(
            'issues',
            FrontModuleController::class,
            'getIssues',
            'Module issues'
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'illuminated.conference.front';
    }

    /**
     * @return string|null
     */
    public function getDefaultMethodName()
    {
        return 'index';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Home';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Provides an overview panel for Conference.';
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'fa-home';
    }
}
