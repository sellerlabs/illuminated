<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Jobs\Interfaces;

use Chromabits\Illuminated\Jobs\Job;

/**
 * Interface JobFactoryInterface.
 *
 * Builds jobs.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs\Interfaces
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
