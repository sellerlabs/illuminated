<?php

namespace Chromabits\Illuminated\Http\Middleware;

use Chromabits\Illuminated\Http\Interfaces\Middleware;
use Closure;
use Illuminate\Http\Request;

/**
 * Class IdentityMiddleware.
 *
 * Dummy middleware.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Http\Middleware
 */
class IdentityMiddleware implements Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
