<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Jobs;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\Paginator;
use SellerLabs\Illuminated\Jobs\Interfaces\JobRepositoryInterface;
use SellerLabs\Nucleus\Foundation\BaseObject;

/**
 * Class JobRepository.
 *
 * Handles common operations on Jobs.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs
 */
class JobRepository extends BaseObject implements JobRepositoryInterface
{
    /**
     * Get a paginated list of jobs.
     *
     * @param int $take
     *
     * @return Paginator
     */
    public function getPaginated($take = 25)
    {
        return Job::query()->orderBy('updated_at', 'desc')->paginate($take);
    }

    /**
     * Get a paginated list of scheduled jobs.
     *
     * @param int $take
     *
     * @return Paginator
     */
    public function getScheduledPaginated($take = 25)
    {
        return Job::query()
            ->where('state', JobState::SCHEDULED)
            ->orderBy('updated_at', 'desc')
            ->paginate($take);
    }

    /**
     * Get a paginated list of failed jobs.
     *
     * @param int $take
     *
     * @return Paginator
     */
    public function getFailedPaginated($take = 25)
    {
        return Job::query()
            ->where('state', JobState::FAILED)
            ->orderBy('updated_at', 'desc')
            ->paginate($take);
    }

    /**
     * Get a paginated list of queued jobs.
     *
     * @param int $take
     *
     * @return Paginator
     */
    public function getQueuedPaginated($take = 25)
    {
        return Job::query()
            ->where('state', JobState::QUEUED)
            ->orderBy('updated_at', 'desc')
            ->paginate($take);
    }

    /**
     * Find a specific job.
     *
     * @param string|int $jobId
     *
     * @throws ModelNotFoundException
     * @return Job
     */
    public function find($jobId)
    {
        return Job::query()->where('id', $jobId)->firstOrFail();
    }

    /**
     * Delete a specific job.
     *
     * @param mixed $jobId
     *
     * @return mixed
     */
    public function delete($jobId)
    {
        return Job::query()->where('id', $jobId)->delete();
    }

    /**
     * Mark a job as failed.
     *
     * @param Job $job
     * @param string|null $message
     */
    public function fail(Job $job, $message = null)
    {
        $job->state = JobState::FAILED;

        if (!is_null($message)) {
            $job->message = $message;
        }

        $job->save();
    }

    /**
     * Mark a job as completed.
     *
     * @param Job $job
     * @param string|null $message
     */
    public function complete(Job $job, $message = null)
    {
        $job->state = JobState::COMPLETE;
        $job->completed_at = Carbon::now();

        if (!is_null($message)) {
            $job->message = $message;
        }

        $job->save();
    }

    /**
     * Mark a job as running.
     *
     * @param Job $job
     * @param string|null $message
     */
    public function started(Job $job, $message = null)
    {
        $job->state = JobState::RUNNING;
        $job->started_at = Carbon::now();
        $job->attempts += 1;

        if (!is_null($message)) {
            $job->message = $message;
        }

        $job->save();
    }

    /**
     * Mark a job as released.
     *
     * This is occurs when a job has failed in the past and it still has
     * attempts remaining.
     *
     * @param Job $job
     * @param null $message
     */
    public function release(Job $job, $message = null)
    {
        $job->state = JobState::QUEUED;

        if (!is_null($message)) {
            $job->message = $message;
        }

        $job->save();
    }

    /**
     * Make sure this job does not run again even if it has retries left.
     *
     * This should be used when a problem is detected with the way the job or
     * task are coded which prevents them from running correctly.
     *
     * @param Job $job
     * @param null $message
     */
    public function giveUp(Job $job, $message = null)
    {
        $job->state = JobState::FAILED;
        $job->attempts = $job->retries + 1;

        if (!is_null($message)) {
            $job->message = $message;
        }

        $job->save();
    }
}
