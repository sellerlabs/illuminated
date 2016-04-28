<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Queue;

use Illuminate\Queue\QueueManager;
use SellerLabs\Illuminated\Queue\Interfaces\QueuePusherInterface;
use SellerLabs\Nucleus\Foundation\BaseObject;

/**
 * Class QueuePusher.
 *
 * Utility class for pushing jobs into queues.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Queue
 */
class QueuePusher extends BaseObject implements QueuePusherInterface
{
    /**
     * @var QueueManager
     */
    protected $manager;

    /**
     * Instantiate a QueuePusher.
     *
     * @param QueueManager $manager
     */
    public function __construct(QueueManager $manager)
    {
        parent::__construct();

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
     * @param mixed $job
     * @param array $data
     * @param string $connection Name of the connection
     * @param string $queue
     *
     * @return mixed
     */
    public function push($job, array $data, $connection = null, $queue = null)
    {
        if ($connection == null) {
            return $this->manager->push($job, $data, $queue);
        }

        $connection = $this->manager->connection($connection);

        return $connection->push($job, $data, $queue);
    }
}
