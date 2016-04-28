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

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use SellerLabs\Illuminated\Jobs\Job;
use SellerLabs\Illuminated\Jobs\JobTag;

/**
 * Interface JobSchedulerInterface.
 *
 * Handles job scheduling, cancelling, tracking.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs\Interfaces
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
     * @param string $tag
     *
     * @return JobTag|null
     */
    public function tag(Job $job, $tag);

    /**
     * Find jobs by a tag.
     *
     * @param string $tag
     * @param bool $activeOnly
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
