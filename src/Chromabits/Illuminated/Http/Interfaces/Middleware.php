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

use Closure;
use Illuminate\Http\Request;

/**
 * Interface Middleware.
 *
 * This interface used to be in Laravel 5. Now its deprecated. Here, we redefine
 * it again because it was useful.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Http\Interfaces
 */
interface Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next);
}
