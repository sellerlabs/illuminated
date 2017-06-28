<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Queue\Interfaces;

/**
 * Interface QueuePusherInterface.
 *
 * Utility class for pushing jobs into queues.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Queue\Interfaces
 */
interface QueuePusherInterface
{
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
    public function push($job, array $data, $connection, $queue = null);
}
