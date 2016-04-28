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

use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Collection;
use Mockery as m;
use Mockery\MockInterface;
use SellerLabs\Illuminated\Jobs\Commands\EnqueueScheduledCommand;
use SellerLabs\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use SellerLabs\Illuminated\Jobs\Job;
use SellerLabs\Illuminated\Jobs\JobState;
use SellerLabs\Illuminated\Queue\Interfaces\QueuePusherInterface;
use SellerLabs\Nucleus\Testing\Impersonator;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\SellerLabs\Illuminated\Jobs\JobsDatabaseTrait;
use Tests\SellerLabs\Support\IlluminatedTestCase;

/**
 * Class EnqueueScheduledCommandTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Jobs\Commands
 */
class EnqueueScheduledCommandTest extends IlluminatedTestCase
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
        $job->save();

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

        $this->assertEquals(JobState::QUEUED, $job->fresh()->state);
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
        $job->save();

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
