<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\Chromabits\Illuminated\Queue;

use Chromabits\Illuminated\Queue\QueuePusher;
use Chromabits\Nucleus\Testing\Impersonator;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\QueueManager;
use Mockery as m;
use Mockery\MockInterface;
use Tests\Chromabits\Support\HelpersTestCase;

/**
 * Class QueuePusherTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Queue
 */
class QueuePusherTest extends HelpersTestCase
{
    public function testPush()
    {
        $imp = new Impersonator();

        $job = 'Omg\Doge\LaunchCommand';
        $data = [
            'omg' => 'whyyoudothis.jpg',
        ];
        $queueName = 'rainbows';

        $queue = m::mock(Queue::class);
        $queue->shouldReceive('push')->with($job, $data, $queueName)->once();

        $imp->mock(
            QueueManager::class,
            function (MockInterface $mock) use ($queue) {
                $mock->shouldReceive('connection')->with('dogemq')->once()
                    ->andReturn($queue);
            }
        );

        $pusher = $imp->make(QueuePusher::class);
        $pusher->push($job, $data, 'dogemq', $queueName);
    }
}
