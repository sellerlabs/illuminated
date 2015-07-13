<?php

/**
 * Copyright 2015, Eduardo Trujillo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Laravel Helpers package
 */

namespace Tests\Chromabits\Illuminated\Jobs;

use Chromabits\Illuminated\Jobs\Job;
use Chromabits\Illuminated\Jobs\JobFactory;
use Chromabits\Illuminated\Jobs\JobState;
use InvalidArgumentException;
use Tests\Chromabits\Support\HelpersTestCase;

/**
 * Class JobFactoryTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Jobs
 */
class JobFactoryTest extends HelpersTestCase
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
