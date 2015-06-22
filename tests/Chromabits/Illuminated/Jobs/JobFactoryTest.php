<?php

namespace Tests\Chromabits\Illuminated\Jobs;

use Chromabits\Illuminated\Jobs\Job;
use Chromabits\Illuminated\Jobs\JobFactory;
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
}
