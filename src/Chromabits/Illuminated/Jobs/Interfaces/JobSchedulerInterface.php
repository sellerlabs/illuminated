<?php

namespace Chromabits\Illuminated\Jobs\Interfaces;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Chromabits\Illuminated\Jobs\Job;

/**
 * Interface JobSchedulerInterface
 *
 * Handles job scheduling, cancelling, tracking.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs\Interfaces
 */
interface JobSchedulerInterface
{
    /**
     * Schedule a job to run after a specific time in the future.
     *
     * @param Job $job
     * @param Carbon $runAt
     */
    public function push(Job $job, Carbon $runAt);

    /**
     * Get jobs that have been scheduled an ready to run.
     *
     * @param int $take
     *
     * @return Collection
     */
    public function findReady($take = 20);

    /**
     * Cancel a job.
     *
     * Note: If the job was pushed on a queue, it won't be removed. However,
     * workers should query the DB to check if it has been cancelled before
     * running them.
     *
     * @param Job $job
     */
    public function cancel(Job $job);
}
