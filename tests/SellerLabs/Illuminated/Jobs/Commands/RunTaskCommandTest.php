<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Jobs\Commands;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\Jobs\Job as LaravelJob;
use Mockery as m;
use Mockery\MockInterface;
use SellerLabs\Illuminated\Jobs\Commands\RunTaskCommand;
use SellerLabs\Illuminated\Jobs\Exceptions\UnresolvableException;
use SellerLabs\Illuminated\Jobs\Interfaces\HandlerResolverInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobRepositoryInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use SellerLabs\Illuminated\Jobs\Job;
use SellerLabs\Illuminated\Jobs\JobState;
use SellerLabs\Illuminated\Jobs\Tasks\BaseTask;
use SellerLabs\Nucleus\Meditation\Boa;
use SellerLabs\Nucleus\Meditation\Spec;
use SellerLabs\Nucleus\Testing\Impersonator;
use Tests\SellerLabs\Illuminated\Jobs\JobsDatabaseTrait;
use Tests\SellerLabs\Support\IlluminatedTestCase;

/**
 * Class RunTaskCommandTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Jobs\Commands
 */
class RunTaskCommandTest extends IlluminatedTestCase
{
    use JobsDatabaseTrait;

    /**
     * Setup the test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->migrateJobsDatabase();
    }

    public function testConstructor()
    {
        $impersonator = new Impersonator();

        $impersonator->make(RunTaskCommand::class);
    }

    public function testFire()
    {
        $laravelJob = m::mock(LaravelJob::class);

        $job = new Job();
        $job->state = JobState::QUEUED;

        $handler = m::mock(BaseTask::class);
        $handler->shouldReceive('fire')->with(
            $job,
            m::type(JobSchedulerInterface::class)
        )->atLeast()->once();
        $handler->shouldReceive('getSpec')->andReturnNull();

        $impersonator = new Impersonator();

        $impersonator->mock(
            JobRepositoryInterface::class,
            function (MockInterface $mock) use ($job) {
                $mock->shouldReceive('find')->with(1337)->atLeast()->once()
                    ->andReturn($job);

                $mock->shouldReceive('started')->with($job, m::type('string'))
                    ->atLeast()->once();

                $mock->shouldReceive('complete')->with($job)
                    ->atLeast()->once();
            }
        );

        $impersonator->mock(
            HandlerResolverInterface::class,
            function (MockInterface $mock) use ($job, $handler) {
                $mock->shouldReceive('resolve')->with($job)->atLeast()->once()
                    ->andReturn($handler);
            }
        );

        /** @var RunTaskCommand $command */
        $command = $impersonator->make(RunTaskCommand::class);

        $command->fire($laravelJob, ['job_id' => 1337]);
    }

    public function testFireWithNoFound()
    {
        $laravelJob = m::mock(LaravelJob::class);
        $laravelJob->shouldReceive('delete')->atLeast()->once();

        $job = new Job();
        $job->state = JobState::QUEUED;

        $impersonator = new Impersonator();

        $impersonator->mock(
            JobRepositoryInterface::class,
            function (MockInterface $mock) use ($job) {
                $mock->shouldReceive('find')->with(1337)->atLeast()->once()
                    ->andThrow(ModelNotFoundException::class);
            }
        );

        /** @var RunTaskCommand $command */
        $command = $impersonator->make(RunTaskCommand::class);

        $command->fire($laravelJob, ['job_id' => 1337]);
    }

    public function testFireWithUnresolvable()
    {
        $laravelJob = m::mock(LaravelJob::class);
        $laravelJob->shouldReceive('delete')->atLeast()->once();

        $job = new Job();
        $job->state = JobState::QUEUED;

        $impersonator = new Impersonator();

        $impersonator->mock(
            JobRepositoryInterface::class,
            function (MockInterface $mock) use ($job) {
                $mock->shouldReceive('find')->with(1337)->atLeast()->once()
                    ->andReturn($job);

                $mock->shouldReceive('giveUp')->with($job, m::type('string'))
                    ->atLeast()->once();
            }
        );

        $impersonator->mock(
            HandlerResolverInterface::class,
            function (MockInterface $mock) use ($job) {
                $mock->shouldReceive('resolve')->with($job)->atLeast()->once()
                    ->andThrow(UnresolvableException::class);
            }
        );

        /** @var RunTaskCommand $command */
        $command = $impersonator->make(RunTaskCommand::class);

        $command->fire($laravelJob, ['job_id' => 1337]);
    }

    public function testFireWithException()
    {
        $laravelJob = m::mock(LaravelJob::class);
        $laravelJob->shouldReceive('delete')->atLeast()->once();

        $job = new Job();
        $job->state = JobState::QUEUED;

        $handler = m::mock(BaseTask::class);
        $handler->shouldReceive('fire')->with(
            $job,
            m::type(JobSchedulerInterface::class)
        )->atLeast()->once()->andThrow('Exception');
        $handler->shouldReceive('getSpec')->andReturnNull();

        $impersonator = new Impersonator();

        $impersonator->mock(
            JobRepositoryInterface::class,
            function (MockInterface $mock) use ($job) {
                $mock->shouldReceive('find')->with(1337)->atLeast()->once()
                    ->andReturn($job);

                $mock->shouldReceive('started')->with($job, m::type('string'))
                    ->atLeast()->once();

                $mock->shouldReceive('fail')->with($job, m::type('string'))
                    ->atLeast()->once();
            }
        );

        $impersonator->mock(
            HandlerResolverInterface::class,
            function (MockInterface $mock) use ($job, $handler) {
                $mock->shouldReceive('resolve')->with($job)->atLeast()->once()
                    ->andReturn($handler);
            }
        );

        /** @var RunTaskCommand $command */
        $command = $impersonator->make(RunTaskCommand::class);

        $command->fire($laravelJob, ['job_id' => 1337]);
    }

    public function testFireWithInvalidSpec()
    {
        $laravelJob = m::mock(LaravelJob::class);
        $laravelJob->shouldReceive('delete')->atLeast()->once();

        $job = new Job();
        $job->state = JobState::QUEUED;

        $handler = m::mock(BaseTask::class);
        $handler->shouldReceive('fire')->with(
            $job,
            m::type(JobSchedulerInterface::class)
        )->atLeast()->once();
        $handler->shouldReceive('getSpec')->andReturn(Spec::define([
            'omg' => Boa::boolean(),
        ], [
            'yes' => 'please',
        ], ['why_not']));

        $impersonator = new Impersonator();

        $impersonator->mock(
            JobRepositoryInterface::class,
            function (MockInterface $mock) use ($job) {
                $mock->shouldReceive('find')->with(1337)->atLeast()->once()
                    ->andReturn($job);

                $mock->shouldReceive('started')->with($job, m::type('string'))
                    ->atLeast()->once();

                $mock->shouldReceive('giveUp')->with($job, m::type('string'))
                    ->atLeast()->once();
            }
        );

        $impersonator->mock(
            HandlerResolverInterface::class,
            function (MockInterface $mock) use ($job, $handler) {
                $mock->shouldReceive('resolve')->with($job)->atLeast()->once()
                    ->andReturn($handler);
            }
        );

        /** @var RunTaskCommand $command */
        $command = $impersonator->make(RunTaskCommand::class);

        $command->fire($laravelJob, ['job_id' => 1337]);
    }

    public function testFireWithValidSpec()
    {
        $laravelJob = m::mock(LaravelJob::class);
        $laravelJob->shouldReceive('delete')->atLeast()->once();

        $job = new Job();
        $job->state = JobState::QUEUED;
        $job->data = json_encode([
            'omg' => false,
            'why_not' => 'because',
        ]);

        $handler = m::mock(BaseTask::class);
        $handler->shouldReceive('fire')->with(
            $job,
            m::type(JobSchedulerInterface::class)
        )->atLeast()->once();
        $handler->shouldReceive('getSpec')->andReturn(Spec::define([
            'omg' => Boa::boolean(),
        ], [
            'yes' => 'please',
        ], ['why_not']));

        $impersonator = new Impersonator();

        $impersonator->mock(
            JobRepositoryInterface::class,
            function (MockInterface $mock) use ($job) {
                $mock->shouldReceive('find')->with(1337)->atLeast()->once()
                    ->andReturn($job);

                $mock->shouldReceive('started')->with($job, m::type('string'))
                    ->atLeast()->once();

                $mock->shouldReceive('complete')->with($job)
                    ->atLeast()->once();
            }
        );

        $impersonator->mock(
            HandlerResolverInterface::class,
            function (MockInterface $mock) use ($job, $handler) {
                $mock->shouldReceive('resolve')->with($job)->atLeast()->once()
                    ->andReturn($handler);
            }
        );

        /** @var RunTaskCommand $command */
        $command = $impersonator->make(RunTaskCommand::class);

        $command->fire($laravelJob, ['job_id' => 1337]);
    }

    public function testFireWithExceptionAndRetries()
    {
        $laravelJob = m::mock(LaravelJob::class);
        $laravelJob->shouldReceive('release')->atLeast()->once();

        $job = new Job();
        $job->task = 'test.test';
        $job->state = JobState::QUEUED;
        $job->attempts = 0;
        $job->retries = 5;
        $job->save();

        $handler = m::mock(BaseTask::class);
        $handler->shouldReceive('fire')->with(
            $job,
            m::type(JobSchedulerInterface::class)
        )->atLeast()->once()->andThrow('Exception');

        $impersonator = new Impersonator();

        $impersonator->mock(
            JobRepositoryInterface::class,
            function (MockInterface $mock) use ($job) {
                $mock->shouldReceive('find')->with(1337)->atLeast()->once()
                    ->andReturn($job);

                $mock->shouldReceive('started')->with($job, m::type('string'))
                    ->atLeast()->once();

                $mock->shouldReceive('release')->with($job)->atLeast()->once();
            }
        );

        $impersonator->mock(
            HandlerResolverInterface::class,
            function (MockInterface $mock) use ($job, $handler) {
                $mock->shouldReceive('resolve')->with($job)->atLeast()->once()
                    ->andReturn($handler);
            }
        );

        /** @var RunTaskCommand $command */
        $command = $impersonator->make(RunTaskCommand::class);

        $command->fire($laravelJob, ['job_id' => 1337]);
    }

    public function testFireWithEarlyException()
    {
        $laravelJob = m::mock(LaravelJob::class);
        $laravelJob->shouldReceive('delete')->atLeast()->once();

        $impersonator = new Impersonator();

        $impersonator->mock(
            JobRepositoryInterface::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('find')->with(1337)->atLeast()->once()
                    ->andThrow('Exception');
            }
        );

        /** @var RunTaskCommand $command */
        $command = $impersonator->make(RunTaskCommand::class);

        $command->fire($laravelJob, ['job_id' => 1337]);
    }
}
