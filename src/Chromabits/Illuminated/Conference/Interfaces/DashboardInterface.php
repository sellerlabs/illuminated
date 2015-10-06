<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Conference\Interfaces;

use Chromabits\Illuminated\Conference\Entities\ConferenceContext;
use Chromabits\Illuminated\Conference\Entities\SidebarPanelPair;
use Chromabits\Illuminated\Conference\Module;
use Chromabits\Nucleus\Exceptions\CoreException;
use Chromabits\Nucleus\Meditation\Exceptions\InvalidArgumentException;
use Exception;
use Illuminate\Http\Request;

/**
 * Interface DashboardInterface.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Conference\Interfaces
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
