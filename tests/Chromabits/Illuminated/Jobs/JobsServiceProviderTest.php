<?php

/**
 * Copyright 2015, Eduardo Trujillo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Laravel Helpers package
 */

namespace Chromabits\Illuminated\Jobs;

use Chromabits\Illuminated\Jobs\Interfaces\HandlerResolverInterface;
use Chromabits\Illuminated\Jobs\Interfaces\JobFactoryInterface;
use Chromabits\Illuminated\Jobs\Interfaces\JobRepositoryInterface;
use Chromabits\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use Chromabits\Illuminated\Testing\ServiceProviderTestCase;
use Illuminate\Foundation\Application;

/**
 * Class JobsServiceProviderTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs
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
     * Make an instance of the service provider being tested
     *
     * @param Application $app
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function make(Application $app)
    {
        return new JobsServiceProvider($app);
    }
}
