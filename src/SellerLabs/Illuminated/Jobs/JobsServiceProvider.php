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

use SellerLabs\Illuminated\Jobs\Commands\EnqueueDaemonCommand;
use SellerLabs\Illuminated\Jobs\Commands\EnqueueScheduledCommand;
use SellerLabs\Illuminated\Jobs\Interfaces\HandlerResolverInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobFactoryInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobRepositoryInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use SellerLabs\Illuminated\Support\ServiceProvider;

/**
 * Class JobsServiceProvider.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs
 */
class JobsServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the service provider.
     *
     */
    public function register()
    {
        $this->app->bind(JobRepositoryInterface::class, JobRepository::class);
        $this->app->bind(JobSchedulerInterface::class, JobScheduler::class);
        $this->app->bind(
            HandlerResolverInterface::class,
            HandlerResolver::class
        );
        $this->app->bind(JobFactoryInterface::class, JobFactory::class);

        $this->commands([
            EnqueueScheduledCommand::class,
            EnqueueDaemonCommand::class,
        ]);
    }

    /**
     * Return a list of services provided.
     *
     * @return array
     */
    public function provides()
    {
        return [
            JobRepositoryInterface::class,
            JobSchedulerInterface::class,
            HandlerResolverInterface::class,
            JobFactoryInterface::class,
        ];
    }
}
