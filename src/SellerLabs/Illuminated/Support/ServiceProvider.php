<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Support;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use SellerLabs\Nucleus\Exceptions\LackOfCoffeeException;

/**
 * Class ServiceProvider.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Support
 */
abstract class ServiceProvider extends BaseServiceProvider
{
    protected $defer = null;

    /**
     * Create a new service provider instance.
     *
     * @param Application $app
     *
     * @throws LackOfCoffeeException
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        if ($this->defer === null) {
            throw new LackOfCoffeeException(
                'Please define whether this provider should be deferred or not.'
            );
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @throws LackOfCoffeeException
     * @return array
     */
    public function provides()
    {
        if ($this->defer === false) {
            throw new LackOfCoffeeException(
                'Service provider is deferred but it does not declare which'
                    . ' services it provides on the `provides()` method.'
            );
        }

        return [];
    }
}
