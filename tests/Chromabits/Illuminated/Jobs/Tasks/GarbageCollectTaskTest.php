<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\Chromabits\Illuminated\Jobs\Tasks;

use Carbon\Carbon;
use Chromabits\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use Chromabits\Illuminated\Jobs\Job;
use Chromabits\Illuminated\Jobs\JobState;
use Chromabits\Illuminated\Jobs\Tasks\GarbageCollectTask;
use Mockery as m;
use Tests\Chromabits\Illuminated\Jobs\JobsDatabaseTrait;

/**
 * Class GarbageCollectTaskTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Jobs\Tasks
 */
class GarbageCollectTaskTest extends TaskTestCase
{
    use JobsDatabaseTrait;

    protected function setUp()
    {
        parent::setUp();

        $this->migrateJobsDatabase();
    }

    public function testFire()
    {
        $task = new GarbageCollectTask();

        $job = new Job();
        $job->task = 'test.test';
        $job->state = JobState::SCHEDULED;
        $job->created_at = Carbon::now()->subDays(32);
        $job->save();

        $job2 = new Job();
        $job2->task = 'test.test2';
        $job2->state = JobState::FAILED;
        $job2->created_at = Carbon::now()->subDays(32);
        $job2->save();

        $job3 = new Job();
        $job3->task = 'test.test3';
        $job3->state = JobState::COMPLETE;
        $job3->created_at = Carbon::now()->subDays(32);
        $job3->save();

        $job4 = new Job();
        $job4->task = 'test.test4';
        $job4->state = JobState::CANCELLED;
        $job4->created_at = Carbon::now()->subDays(32);
        $job4->save();

        $job5 = new Job();
        $job5->task = 'test.test5';
        $job5->created_at = Carbon::now()->subDays(28);
        $job5->save();

        $gcJob = new Job();
        $gcJob->task = 'jobs.gc';
        $gcJob->created_at = Carbon::now()->subDays(1);
        $gcJob->save();

        $task->fire($gcJob, m::mock(JobSchedulerInterface::class));

        $this->assertNotNull(Job::find($job->id));
        $this->assertNull(Job::find($job2->id));
        $this->assertNull(Job::find($job3->id));
        $this->assertNull(Job::find($job4->id));
        $this->assertNotNull(Job::find($job5->id));
    }

    /**
     * Make an instance of the task being tested.
     *
     * @return mixed
     */
    public function make()
    {
        return new GarbageCollectTask();
    }
}
