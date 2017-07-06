<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Hashing;

use Illuminate\Contracts\Hashing\Hasher;
use SellerLabs\Illuminated\Support\ServiceProvider;

/**
 * Class AggregatedHashServiceProvider.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Hashing
 */
class AggregatedHashServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $hasher = new AggregatedHasher();

        $this->app->singleton('hash', $hasher);
        $this->app->singleton(Hasher::class, $hasher);
    }

    /**
     * Return list of services provided.
     *
     * @return array
     */
    public function provides()
    {
        return ['hash', Hasher::class];
    }
}
