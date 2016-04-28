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

use InvalidArgumentException;
use SellerLabs\Illuminated\Jobs\Job;
use SellerLabs\Illuminated\Jobs\JobFactory;
use SellerLabs\Illuminated\Jobs\JobState;
use SellerLabs\Nucleus\Support\Str;
use Tests\SellerLabs\Support\IlluminatedTestCase;

/**
 * Class JobFactoryTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Jobs
 */
class JobFactoryTest extends IlluminatedTestCase
{
    public function testMake()
    {
        $factory = new JobFactory();

        $job = $factory->make('test.test', '{"wow":"such task"}', 7);

        $this->assertInstanceOf(Job::class, $job);
        $this->assertEquals('test.test', $job->task);
        $this->assertEquals(7, $job->retries);
    }

    public function testMakeWithInvalidJson()
    {
        $factory = new JobFactory();

        $this->setExpectedException(InvalidArgumentException::class);

        $factory->make('test.test', '{wow:"such task"};', 7);
    }

    public function testMakeWithHugeJson()
    {
        $factory = new JobFactory();
        $huge = Str::random(2 ** 16);

        $this->setExpectedException(InvalidArgumentException::class);

        $factory->make('test.test', ['huge' => $huge], 7);
    }

    public function testMakeWithArray()
    {
        $factory = new JobFactory();

        $job = $factory->make('test.test', ['wow' => 'such task'], 7);

        $this->assertInstanceOf(Job::class, $job);
        $this->assertEquals('{"wow":"such task"}', $job->data);
    }

    public function testMakeWithEmpty()
    {
        $factory = new JobFactory();

        $job = $factory->make('test.test', '', 7);

        $this->assertInstanceOf(Job::class, $job);
        $this->assertEquals('{}', $job->data);
    }

    public function testDuplicate()
    {
        $factory = new JobFactory();

        $job = $factory->make('test.test', ['wow' => 'such task'], 7);
        $job->state = JobState::COMPLETE;

        $newJob = $factory->duplicate($job);

        $this->assertEquals(JobState::IDLE, $newJob->state);
        $this->assertEquals('test.test', $newJob->task);
        $this->assertEquals(json_encode(['wow' => 'such task']), $newJob->data);
        $this->assertEquals(7, $newJob->retries);
    }
}
