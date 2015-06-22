<?php

namespace Tests\Chromabits\Illuminated\Jobs;

use Carbon\Carbon;
use Chromabits\Illuminated\Jobs\Job;
use Chromabits\Illuminated\Jobs\JobScheduler;
use Chromabits\Illuminated\Jobs\JobState;
use Chromabits\Nucleus\Testing\Impersonator;
use InvalidArgumentException;
use Tests\Chromabits\Support\HelpersTestCase;

/**
 * Class JobSchedulerTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Jobs
 */
class JobSchedulerTest extends HelpersTestCase
{
    use JobsDatabaseTrait;

    protected function setUp()
    {
        parent::setUp();

        $this->migrateJobsDatabase();
    }

    public function testPush()
    {
        $impersonator = new Impersonator();

        /** @var JobScheduler $scheduler */
        $scheduler = $impersonator->make(JobScheduler::class);

        $job = new Job();
        $job->task = 'test.test';
        $job->state = JobState::IDLE;

        $tomorrow = Carbon::tomorrow();

        $scheduler->push($job, $tomorrow);

        $this->assertEquals(JobState::SCHEDULED, $job->state);
        $this->assertEquals($tomorrow, $job->run_at);
        $this->assertTrue($job->schedulable);
    }

    public function testPushWithPast()
    {
        $impersonator = new Impersonator();

        /** @var JobScheduler $scheduler */
        $scheduler = $impersonator->make(JobScheduler::class);

        $job = new Job();
        $job->task = 'test.test';
        $job->state = JobState::IDLE;

        $this->setExpectedException(InvalidArgumentException::class);

        $scheduler->push($job, Carbon::yesterday());
    }

    public function testFindReady()
    {
        $job = new Job();
        $job->task = 'test.test';
        $job->state = JobState::SCHEDULED;
        $job->run_at = Carbon::now()->subDay();
        $job->save();

        $job2 = new Job();
        $job2->task = 'test.test';
        $job2->state = JobState::QUEUED;
        $job2->save();

        $job3 = new Job();
        $job3->task = 'test.test';
        $job3->state = JobState::SCHEDULED;
        $job3->run_at = Carbon::now()->addYear();
        $job3->save();

        $job4 = new Job();
        $job4->task = 'test.test';
        $job4->state = JobState::SCHEDULED;
        $job4->save();

        $impersonator = new Impersonator();

        /** @var JobScheduler $scheduler */
        $scheduler = $impersonator->make(JobScheduler::class);

        $result1 = $scheduler->findReady();
        $this->assertEquals(1, $result1->count());
        $this->assertEquals($job->id, $result1[0]->id);

        $job4->expires_at = Carbon::now()->addYear();
        $job4->run_at = Carbon::now()->subDays(2);
        $job4->save();

        $result2 = $scheduler->findReady();
        $this->assertEquals(2, $result2->count());
        $this->assertEquals($job4->id, $result2[0]->id);
        $this->assertEquals($job->id, $result2[1]->id);

        $result3 = $scheduler->findReady(1);
        $this->assertEquals(1, $result3->count());
        $this->assertEquals($job4->id, $result3[0]->id);

        $job4->state = JobState::RUNNING;
        $job4->save();

        $result4 = $scheduler->findReady(1);
        $this->assertEquals(1, $result4->count());
        $this->assertEquals($job->id, $result4[0]->id);
    }

    public function testCancel()
    {
        $impersonator = new Impersonator();

        /** @var JobScheduler $scheduler */
        $scheduler = $impersonator->make(JobScheduler::class);

        $job = new Job();
        $job->task = 'test.test';
        $job->state = JobState::SCHEDULED;

        $scheduler->cancel($job);

        $this->assertEquals(JobState::CANCELLED, $job->state);
    }
}
