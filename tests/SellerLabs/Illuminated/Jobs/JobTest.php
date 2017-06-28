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
use SellerLabs\Illuminated\Jobs\Job;
use SellerLabs\Illuminated\Jobs\JobState;
use SellerLabs\Nucleus\Support\Str;
use Tests\SellerLabs\Support\IlluminatedTestCase;

/**
 * Class JobTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Jobs
 */
class JobTest extends IlluminatedTestCase
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

    public function testReady()
    {
        $job = new Job();

        $job->state = JobState::QUEUED;

        $this->assertTrue($job->ready());

        $job->state = JobState::COMPLETE;
        $this->assertFalse($job->ready());

        $job->state = JobState::SCHEDULED;
        $job->run_at = Carbon::yesterday();

        $this->assertTrue($job->ready());

        $job->state = JobState::SCHEDULED;
        $job->run_at = Carbon::tomorrow();

        $this->assertFalse($job->ready());

        $job->state = JobState::SCHEDULED;
        $job->run_at = Carbon::yesterday();
        $job->expires_at = Carbon::yesterday()->addHour();

        $this->assertFalse($job->ready());
    }

    public function testExpired()
    {
        $job = new Job();

        $this->assertFalse($job->expired());

        $job->expires_at = Carbon::tomorrow();
        $this->assertFalse($job->expired());

        $job->expires_at = Carbon::yesterday();
        $this->assertTrue($job->expired());
    }

    public function testWillExpire()
    {
        $job = new Job();

        $this->assertFalse($job->willExpire());

        $job->expires_at = Carbon::tomorrow();

        $this->assertTrue($job->willExpire());
    }

    public function testGetExecutionTime()
    {
        $job = new Job();

        $this->assertEquals('N/A', $job->getExecutionTime());

        $job->started_at = Carbon::yesterday();
        $job->completed_at = Carbon::yesterday()->addMinutes(5);

        $this->assertEquals('5 minutes', $job->getExecutionTime());
    }

    public function testHasAttemptsLeft()
    {
        $job = new Job();

        $this->assertFalse($job->hasAttemptsLeft());

        $job->attempts = 0;
        $job->retries = 1;

        $this->assertTrue($job->hasAttemptsLeft());

        $job->attempts += 1;

        $this->assertFalse($job->hasAttemptsLeft());
    }

    public function testGetData()
    {
        $job = new Job();

        $this->assertEquals([], $job->getData());

        $job->data = "{\n    \"doge\": true\n}";

        $this->assertEquals(
            [
                'doge' => true,
            ],
            $job->getData()
        );
    }

    public function testIsCancellable()
    {
        $job = new Job();

        $this->assertFalse($job->isCancellable());

        $job->state = JobState::CANCELLED;
        $this->assertFalse($job->isCancellable());

        $job->state = JobState::COMPLETE;
        $this->assertFalse($job->isCancellable());

        $job->state = JobState::RUNNING;
        $this->assertFalse($job->isCancellable());

        $job->state = JobState::QUEUED;
        $this->assertTrue($job->isCancellable());

        $job->state = JobState::SCHEDULED;
        $this->assertTrue($job->isCancellable());
    }

    public function testGet()
    {
        $job = new Job();

        $job->data = "{\n    \"doge\": true\n}";

        $this->assertEquals(true, $job->get('doge'));
        $this->assertEquals(null, $job->get('doges'));
        $this->assertEquals('wow', $job->get('doges', 'wow'));
    }

    public function testAppend()
    {
        $job = new Job();

        $job->task = 'test.test';

        $this->assertNull($job->message);

        $job->append('lol');
        $this->assertEquals("lol\n", $job->message);

        $job->append('omg');
        $this->assertEquals("lol\nomg\n", $job->message);

        $job->append('doge');
        $this->assertEquals("lol\nomg\ndoge\n", $job->message);

        $huge = Str::quickRandom(200000);

        $job->append($huge);
        $job->append($huge);
        $job->append('doge');

        $this->assertFalse(starts_with($job->message, "lol\nomg\ndoge\n"));
        $this->assertTrue(ends_with($job->message, "doge\n"));
    }
}
