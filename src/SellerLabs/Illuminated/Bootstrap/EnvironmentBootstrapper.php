<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Bootstrap;

use Dotenv\Dotenv;
use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;
use SellerLabs\Illuminated\Bootstrap\Interfaces\BootstrapperInterface;

/**
 * Class EnvironmentBootstrapper.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Bootstrap
 */
class EnvironmentBootstrapper implements BootstrapperInterface
{
    const DEFAULT_ENVIRONMENT = 'stasis';

    /**
     * Bootstrap the given application.
     *
     * @param Application $app
     */
    public function bootstrap(Application $app)
    {
        // Attempt to load the default .env
        try {
            $dotenv = new DotEnv($app->environmentPath(), $app->environmentFile());
            $dotenv->load();
        } catch (InvalidArgumentException $e) {
            //
        }

        // Attempt to load the environment-specific .env
        try {
            $dotenv = new DotEnv($app->environmentPath(), vsprintf('.%s.env', [
                env('APP_ENV', static::DEFAULT_ENVIRONMENT),
            ]));
            $dotenv->load();
        } catch (InvalidArgumentException $e) {
            //
        }

        $app->detectEnvironment(function () {
            return env('APP_ENV', static::DEFAULT_ENVIRONMENT);
        });
    }
}
