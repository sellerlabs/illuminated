<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Jobs;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use SellerLabs\Illuminated\Jobs\Interfaces\HandlerResolverInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobFactoryInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobRepositoryInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use SellerLabs\Illuminated\Testing\ServiceProviderTestCase;

/**
 * Class JobsServiceProviderTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs
 */
class JobsServiceProviderTest extends ServiceProviderTestCase
{
    protected $shouldBeBound = [
        JobRepositoryInterface::class,
        JobSchedulerInterface::class,
        HandlerResolverInterface::class,
        JobFactoryInterface::class,
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
        return new JobsServiceProvider($app);
    }
}
