<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Queue;

use Chromabits\Illuminated\Queue\Interfaces\QueuePusherInterface;
use Illuminate\Queue\QueueManager;

/**
 * Class QueuePusher
 *
 * Utility class for pushing jobs into queues.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Queue
 */
class QueuePusher implements QueuePusherInterface
{
    /**
     * @var QueueManager
     */
    protected $manager;

    /**
     * Instantiate a QueuePusher
     *
     * @param QueueManager $manager
     */
    public function __construct(QueueManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Pushes a job into a specific queue connection.
     *
     * If you are using multiple SQS queues, this method might be useful.
     * Instead of having to provide the whole queue URL every time you want to
     * push a job into it, you just provide the name of the queue connection
     * as set in the configuration file.
     *
     * @param $job
     * @param array $data
     * @param string $connection Name of the connection
     * @param string $queue
     *
     * @return mixed
     */
    public function push($job, array $data, $connection, $queue = null)
    {
        $connection = $this->manager->connection($connection);

        return $connection->push($job, $data, $queue);
    }
}
