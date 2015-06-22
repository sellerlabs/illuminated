<?php

namespace Tests\Chromabits\Illuminated\Jobs\Commands;

use Carbon\Carbon;
use Chromabits\Illuminated\Jobs\Commands\EnqueueScheduledCommand;
use Chromabits\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use Chromabits\Illuminated\Jobs\Job;
use Chromabits\Illuminated\Jobs\JobState;
use Chromabits\Nucleus\Testing\Impersonator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\QueueManager;
use Mockery as m;
use Mockery\MockInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\Chromabits\Illuminated\Jobs\JobsDatabaseTrait;
use Tests\Chromabits\Support\HelpersTestCase;

/**
 * Class EnqueueScheduledCommandTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Jobs\Commands
 */
class EnqueueScheduledCommandTest extends HelpersTestCase
{
    use JobsDatabaseTrait;

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

        $impersonator = new Impersonator();

        $impersonator->mock(
            JobSchedulerInterface::class,
            function (MockInterface $mock) use ($job) {
                $mock->shouldReceive('findReady')->atLeast()->once()
                    ->andReturn(new Collection([$job]));
            }
        );

        $impersonator->mock(
            QueueManager::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('push')->atLeast()->once()
                    ->with(m::type('string'), m::type('array'));
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
