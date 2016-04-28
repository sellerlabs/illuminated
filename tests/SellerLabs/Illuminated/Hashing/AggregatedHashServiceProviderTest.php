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
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use SellerLabs\Illuminated\Testing\ServiceProviderTestCase;

/**
 * Class AggregatedHashServiceProviderTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Hashing
 */
class AggregatedHashServiceProviderTest extends ServiceProviderTestCase
{
    protected $shouldBeBound = [
        'hash',
        Hasher::class,
    ];

    /**
     * Make an instance of the service provider being tested.
     *
     * @param Application $app
     *
     * @return ServiceProvider
     */
    public function make(Application $app)
    {
        return new AggregatedHashServiceProvider($app);
    }
}
