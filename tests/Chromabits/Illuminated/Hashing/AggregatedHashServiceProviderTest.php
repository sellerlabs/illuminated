<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Hashing;

use Chromabits\Illuminated\Testing\ServiceProviderTestCase;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Class AggregatedHashServiceProviderTest.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Hashing
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
