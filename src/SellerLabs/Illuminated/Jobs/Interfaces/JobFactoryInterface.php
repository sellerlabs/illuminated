<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Jobs\Interfaces;

use SellerLabs\Illuminated\Jobs\Job;

/**
 * Interface JobFactoryInterface.
 *
 * Builds jobs.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs\Interfaces
 */
interface JobFactoryInterface
{
    /**
     * Build a job.
     *
     * @param string $task
     * @param string $data
     * @param int $retries
     *
     * @return Job
     */
    public function make($task, $data = '{}', $retries = 0);

    /**
     * Copies basic parameters of a job into a new one:.
     *
     * - Task
     * - Data
     * - Retries
     *
     * @param Job $baseJob
     *
     * @return Job
     */
    public function duplicate(Job $baseJob);
}
