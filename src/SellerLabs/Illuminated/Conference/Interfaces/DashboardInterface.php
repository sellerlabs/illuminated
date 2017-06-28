<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Conference\Interfaces;

use Exception;
use Illuminate\Http\Request;
use SellerLabs\Illuminated\Conference\Entities\ConferenceContext;
use SellerLabs\Illuminated\Conference\Entities\SidebarPanelPair;
use SellerLabs\Illuminated\Conference\Module;
use SellerLabs\Nucleus\Exceptions\CoreException;
use SellerLabs\Nucleus\Meditation\Exceptions\InvalidArgumentException;

/**
 * Interface DashboardInterface.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Interfaces
 */
interface DashboardInterface
{
    /**
     * Register a module with this dashboard.
     *
     * @param string $moduleClassName
     *
     * @throws CoreException
     * @throws InvalidArgumentException
     */
    public function register($moduleClassName);

    /**
     * Get the names of all the registered modules.
     *
     * @return string[]
     */
    public function getModuleNames();

    /**
     * Process an incoming request through the dashboard.
     *
     * @param Request $request
     * @param ConferenceContext $context
     * @param null|string $module
     * @param null|string $method
     *
     * @return SidebarPanelPair
     */
    public function run(
        Request $request,
        ConferenceContext $context,
        $module = null,
        $method = null
    );

    /**
     * @return Module[]
     */
    public function getModules();

    /**
     * @return Exception[]
     */
    public function getFailedModules();
}
