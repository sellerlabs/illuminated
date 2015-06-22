<?php

namespace Chromabits\Illuminated\Jobs\Interfaces;

use Chromabits\Illuminated\Jobs\Job;

/**
 * Interface JobFactoryInterface
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
     * @param $task
     * @param string $data
     * @param int $retries
     *
     * @return Job
     */
    public function make($task, $data = '{}', $retries = 0);
}
