<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Jobs\Tasks;

use Carbon\Carbon;
use Mockery as m;
use SellerLabs\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use SellerLabs\Illuminated\Jobs\Job;
use SellerLabs\Illuminated\Jobs\JobState;
use SellerLabs\Illuminated\Jobs\Tasks\GarbageCollectTask;
use Tests\SellerLabs\Illuminated\Jobs\JobsDatabaseTrait;

/**
 * Class GarbageCollectTaskTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Jobs\Tasks
 */
class GarbageCollectTaskTest extends TaskTestCase
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
