<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Jobs;

use Carbon\Carbon;
use InvalidArgumentException;
use Mockery\MockInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobFactoryInterface;
use SellerLabs\Illuminated\Jobs\Job;
use SellerLabs\Illuminated\Jobs\JobScheduler;
use SellerLabs\Illuminated\Jobs\JobState;
use SellerLabs\Illuminated\Jobs\JobTag;
use SellerLabs\Nucleus\Testing\Impersonator;
use Tests\SellerLabs\Support\IlluminatedTestCase;

/**
 * Class JobSchedulerTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Jobs
 */
class JobSchedulerTest extends IlluminatedTestCase
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

    public function testPushCopy()
    {
        $impersonator = new Impersonator();

        $job = new Job();
        $job->task = 'test.test';
        $job->state = JobState::CANCELLED;

        $tomorrow = Carbon::tomorrow();
        $nextWeek = Carbon::today()->addDays(7);

        $impersonator->mock(
            JobFactoryInterface::class,
            function (MockInterface $mock) use ($job) {
                $mock->shouldReceive('duplicate')->once()->with($job)
                    ->andReturn($job);
            }
        );

        /** @var JobScheduler $scheduler */
        $scheduler = $impersonator->make(JobScheduler::class);

        $result = $scheduler->pushCopy($job, $tomorrow, $nextWeek);

        $this->assertInstanceOf(Job::class, $result);
        $this->assertEquals(JobState::SCHEDULED, $result->state);
        $this->assertEquals($tomorrow, $result->run_at);
        $this->assertEquals($nextWeek, $result->expires_at);
    }

    public function testTag()
    {
        $impersonator = new Impersonator();

        /** @var JobScheduler $scheduler */
        $scheduler = $impersonator->make(JobScheduler::class);

        $job = new Job();
        $job->task = 'test.test';
        $job->state = JobState::SCHEDULED;
        $job->save();

        $result = $scheduler->tag($job, 'wow');

        $this->assertInstanceOf(JobTag::class, $result);

        $result = $scheduler->tag($job, 'wow');

        $this->assertInstanceOf(JobTag::class, $result);
    }

    public function testFindByTag()
    {
        $impersonator = new Impersonator();

        /** @var JobScheduler $scheduler */
        $scheduler = $impersonator->make(JobScheduler::class);

        $job = new Job();
        $job->task = 'test.test';
        $job->state = JobState::SCHEDULED;
        $job->save();

        $this->assertEquals(0, $scheduler->findByTag('wow')->count());

        $scheduler->tag($job, 'wow');
        $scheduler->tag($job, 'omg');
        $scheduler->tag($job, 'wow');

        $this->assertEquals(1, $scheduler->findByTag('wow')->count());
        $this->assertEquals(1, $scheduler->findByTag('omg')->count());

        $job->state = JobState::CANCELLED;
        $job->save();

        $this->assertEquals(0, $scheduler->findByTag('wow')->count());
        $this->assertEquals(0, $scheduler->findByTag('omg')->count());
        $this->assertEquals(1, $scheduler->findByTag('wow', false)->count());
        $this->assertEquals(1, $scheduler->findByTag('omg', false)->count());
    }
}
