<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Http\Interfaces;

use Illuminate\Routing\Router;

/**
 * Interface RouteMapper.
 *
 * An object capable of mapping routes to a controller method or a Closure
 * handler.
 *
 * Design recommendations:
 *
 * - Create a route mapper per component of your application. For example,
 * Billing and Auth should have their routes defined in different mappers. This
 * makes the code easier to navigate as it gets more complex.
 * - This is a class. Feel free to split things into methods, and test them
 * individually if needed.
 * - The RouteMapperTest has some tools for testing mappers.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Http\Interfaces
 */
interface RouteMapper
{
    /**
     * Map routes.
     *
     * @param Router $router
     */
    public function map(Router $router);
}
