<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\Chromabits\Illuminated\Jobs;

use Chromabits\Illuminated\Jobs\Job;
use Chromabits\Illuminated\Jobs\JobRepository;
use Chromabits\Illuminated\Jobs\JobState;
use Illuminate\Contracts\Pagination\Paginator;
use Tests\Chromabits\Support\HelpersTestCase;

/**
 * Class JobRepositoryTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Jobs
 */
class JobRepositoryTest extends HelpersTestCase
{
    use JobsDatabaseTrait;

    protected function setUp()
    {
        parent::setUp();

        $this->migrateJobsDatabase();
    }

    public function testGetPaginated()
    {
        $repository = new JobRepository();

        $result = $repository->getPaginated();

        $this->assertInstanceOf(Paginator::class, $result);
    }

    public function testGetScheduledPaginated()
    {
        $repository = new JobRepository();

        $result = $repository->getScheduledPaginated();

        $this->assertInstanceOf(Paginator::class, $result);
    }

    public function testGetFailedPaginated()
    {
        $repository = new JobRepository();

        $result = $repository->getFailedPaginated();

        $this->assertInstanceOf(Paginator::class, $result);
    }

    public function testFind()
    {
        $repository = new JobRepository();

        $job = new Job();

        $job->task = 'test.test';
        $job->state = JobState::RUNNING;
        $job->attempts = 7;

        $job->save();

        $job2 = $repository->find($job->id);

        $this->assertEquals($job->created_at, $job2->created_at);
    }

    public function testDelete()
    {
        $repository = new JobRepository();

        $job = new Job();

        $job->task = 'test.test';
        $job->state = JobState::RUNNING;
        $job->attempts = 7;

        $job->save();

        $this->assertNotNull(Job::find($job->id));

        $repository->delete($job->id);

        $this->assertNull(Job::find($job->id));
    }

    public function testFail()
    {
        $repository = new JobRepository();

        $job = new Job();

        $job->task = 'test.test';
        $job->state = JobState::RUNNING;
        $job->attempts = 7;

        $repository->fail($job, 'omg doges everywhere');

        $this->assertEquals(JobState::FAILED, $job->state);
        $this->assertEquals(7, $job->attempts);
        $this->assertEquals('omg doges everywhere', $job->message);

        $repository->fail($job);

        $this->assertEquals(JobState::FAILED, $job->state);
        $this->assertEquals(7, $job->attempts);
        $this->assertEquals('omg doges everywhere', $job->message);
    }

    public function testComplete()
    {
        $repository = new JobRepository();

        $job = new Job();

        $job->task = 'test.test';
        $job->state = JobState::RUNNING;
        $job->attempts = 4;
        $job->retries = 100;

        $repository->complete($job, 'done bro');

        $this->assertEquals(JobState::COMPLETE, $job->state);
        $this->assertEquals(4, $job->attempts);
        $this->assertEquals('done bro', $job->message);
        $this->assertNotNull($job->completed_at);

        $repository->complete($job);

        $this->assertEquals(JobState::COMPLETE, $job->state);
        $this->assertEquals(4, $job->attempts);
        $this->assertEquals('done bro', $job->message);
        $this->assertNotNull($job->completed_at);
    }

    public function testStarted()
    {
        $repository = new JobRepository();

        $job = new Job();

        $job->task = 'test.test';
        $job->state = JobState::QUEUED;
        $job->attempts = 4;
        $job->retries = 100;

        $repository->started($job, 'starting...');

        $this->assertEquals(JobState::RUNNING, $job->state);
        $this->assertEquals(5, $job->attempts);
        $this->assertEquals('starting...', $job->message);
        $this->assertNotNull($job->started_at);

        $repository->started($job);

        $this->assertEquals(JobState::RUNNING, $job->state);
        $this->assertEquals(6, $job->attempts);
        $this->assertEquals('starting...', $job->message);
        $this->assertNotNull($job->started_at);
    }

    public function testRelease()
    {
        $repository = new JobRepository();

        $job = new Job();

        $job->task = 'test.test';
        $job->state = JobState::RUNNING;
        $job->attempts = 4;
        $job->retries = 100;

        $repository->release($job, 'giving up... for now');

        $this->assertEquals(JobState::QUEUED, $job->state);
        $this->assertEquals(4, $job->attempts);
        $this->assertEquals('giving up... for now', $job->message);

        $repository->release($job);

        $this->assertEquals(JobState::QUEUED, $job->state);
        $this->assertEquals(4, $job->attempts);
        $this->assertEquals('giving up... for now', $job->message);
    }

    public function testGiveUp()
    {
        $repository = new JobRepository();

        $job = new Job();

        $job->task = 'test.test';
        $job->state = JobState::RUNNING;
        $job->attempts = 4;
        $job->retries = 100;

        $repository->giveUp($job, 'whoops');

        $this->assertEquals(JobState::FAILED, $job->state);
        $this->assertEquals(101, $job->attempts);
        $this->assertEquals('whoops', $job->message);

        $repository->giveUp($job);

        $this->assertEquals(JobState::FAILED, $job->state);
        $this->assertEquals(101, $job->attempts);
        $this->assertEquals('whoops', $job->message);
    }
}
