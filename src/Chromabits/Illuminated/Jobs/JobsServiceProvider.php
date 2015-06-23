<?php

namespace Chromabits\Illuminated\Jobs;

use Chromabits\Illuminated\Jobs\Commands\EnqueueScheduledCommand;
use Chromabits\Illuminated\Jobs\Interfaces\HandlerResolverInterface;
use Chromabits\Illuminated\Jobs\Interfaces\JobFactoryInterface;
use Chromabits\Illuminated\Jobs\Interfaces\JobRepositoryInterface;
use Chromabits\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use Illuminate\Support\ServiceProvider;
use Tests\Chromabits\Illuminated\Jobs\Commands\EnqueueScheduledCommandTest;

/**
 * Class JobsServiceProvider
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs
 */
class JobsServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
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
