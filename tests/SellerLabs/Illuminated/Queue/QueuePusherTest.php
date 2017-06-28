<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Queue;

use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\QueueManager;
use Mockery as m;
use Mockery\MockInterface;
use SellerLabs\Illuminated\Queue\QueuePusher;
use SellerLabs\Nucleus\Testing\Impersonator;
use Tests\SellerLabs\Support\IlluminatedTestCase;

/**
 * Class QueuePusherTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Queue
 */
class QueuePusherTest extends IlluminatedTestCase
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

    public function testPushWithDefault()
    {
        $imp = new Impersonator();

        $job = 'Omg\Doge\LaunchCommand';
        $data = [
            'omg' => 'whyyoudothis.jpg',
        ];

        $queue = m::mock(QueueManager::class);
        $queue->shouldReceive('push')->with($job, $data, 'no no i stay')
            ->once();
        $imp->provide($queue);

        $pusher = $imp->make(QueuePusher::class);
        $pusher->push($job, $data, null, 'no no i stay');
    }
}
