<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\Chromabits\Illuminated\Jobs\Commands;

use Carbon\Carbon;
use Chromabits\Illuminated\Jobs\Commands\EnqueueScheduledCommand;
use Chromabits\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use Chromabits\Illuminated\Jobs\Job;
use Chromabits\Illuminated\Jobs\JobState;
use Chromabits\Illuminated\Queue\Interfaces\QueuePusherInterface;
use Chromabits\Nucleus\Testing\Impersonator;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Collection;
use Mockery as m;
use Mockery\MockInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\Chromabits\Illuminated\Jobs\JobsDatabaseTrait;
use Tests\Chromabits\Support\HelpersTestCase;

/**
 * Class EnqueueScheduledCommandTest.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Jobs\Commands
 */
class EnqueueScheduledCommandTest extends HelpersTestCase
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

    public function testContructor()
    {
        $impersonator = new Impersonator();

        $impersonator->make(EnqueueScheduledCommand::class);
    }

    public function testFire()
    {
        $job = new Job();

        $job->state = JobState::SCHEDULED;
        $job->run_at = Carbon::now()->subMinute();
        $job->task = 'sometask';

        $impersonator = new Impersonator();

        $impersonator->mock(
            JobSchedulerInterface::class,
            function (MockInterface $mock) use ($job) {
                $mock->shouldReceive('findReady')->atLeast()->once()
                    ->andReturn(new Collection([$job]));
            }
        );

        $impersonator->mock(
            Repository::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('get')->with('jobs.queue.connection')
                    ->andReturnNull()->once();
                $mock->shouldReceive('get')->with('jobs.queue.id')
                    ->andReturnNull()->once();
            }
        );

        $impersonator->mock(
            QueuePusherInterface::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('push')->atLeast()->once()
                    ->with(m::type('string'), m::type('array'), null, null);
            }
        );

        /** @var EnqueueScheduledCommand $command */
        $command = $impersonator->make(EnqueueScheduledCommand::class);

        $input = new StringInput('--take=25');
        $output = new NullOutput();

        $command->setLaravel($this->app);
        $command->run($input, $output);

        $this->assertEquals(JobState::QUEUED, $job->state);
    }

    public function testFireWithNone()
    {
        $impersonator = new Impersonator();

        $impersonator->mock(
            JobSchedulerInterface::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('findReady')->atLeast()->once()
                    ->andReturn(new Collection([]));
            }
        );

        $impersonator->mock(
            QueuePusherInterface::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('push')->never()
                    ->with(m::type('string'), m::type('array'), null, null);
            }
        );

        /** @var EnqueueScheduledCommand $command */
        $command = $impersonator->make(EnqueueScheduledCommand::class);

        $input = new StringInput('--take=25');
        $output = new NullOutput();

        $command->setLaravel($this->app);
        $command->run($input, $output);
    }

    public function testFireWithCustomQueue()
    {
        $job = new Job();

        $job->state = JobState::SCHEDULED;
        $job->run_at = Carbon::now()->subMinute();
        $job->queue_connection = 'dogemq';
        $job->queue_name = 'food';
        $job->task = 'sometask';

        $impersonator = new Impersonator();

        $impersonator->mock(
            JobSchedulerInterface::class,
            function (MockInterface $mock) use ($job) {
                $mock->shouldReceive('findReady')->atLeast()->once()
                    ->andReturn(new Collection([$job]));
            }
        );

        $impersonator->mock(
            Repository::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('get')->with('jobs.queue.connection')
                    ->andReturn('nopemq')->once();
                $mock->shouldReceive('get')->with('jobs.queue.id')
                    ->andReturn('fakejobs')->once();
            }
        );

        $impersonator->mock(
            QueuePusherInterface::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('push')->atLeast()->once()
                    ->with(
                        m::type('string'),
                        m::type('array'),
                        'dogemq',
                        'food'
                    );
            }
        );

        /** @var EnqueueScheduledCommand $command */
        $command = $impersonator->make(EnqueueScheduledCommand::class);

        $input = new StringInput('--take=25');
        $output = new NullOutput();

        $command->setLaravel($this->app);
        $command->run($input, $output);
    }
}
