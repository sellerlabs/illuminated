<?php

/**
 * Copyright 2015, Eduardo Trujillo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Laravel Helpers package
 */

namespace Chromabits\Illuminated\Jobs\Interfaces;

use Carbon\Carbon;
use Chromabits\Illuminated\Jobs\Job;
use Chromabits\Illuminated\Jobs\JobTag;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * Apply a tag to an existing job.
     *
     * @param Job $job
     * @param $tag
     *
     * @return JobTag|\Illuminate\Database\Eloquent\Model|null|static
     */
    public function tag(Job $job, $tag);

    /**
     * Find jobs by a tag.
     *
     * @param $tag
     * @param bool|true $activeOnly
     * @param int $take
     *
     * @return mixed
     */
    public function findByTag($tag, $activeOnly = true, $take = 20);

    /**
     * Creates a partial copy of the provided job and then schedules it at the
     * specified time.
     *
     * With this, jobs can reschedule themselves to run again at some point in
     * the future. Recurring tasks such as billing are a perfect example of
     * this.
     *
     * @param Job $baseJob
     * @param Carbon $runAt
     * @param Carbon $expiresAt
     *
     * @return Job
     */
    public function pushCopy(Job $baseJob, Carbon $runAt, Carbon $expiresAt);
}
