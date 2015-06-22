<?php

namespace Chromabits\Illuminated\Jobs;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;
use Chromabits\Illuminated\Jobs\Interfaces\JobRepositoryInterface;
use Chromabits\Illuminated\Jobs\Interfaces\JobSchedulerInterface;

/**
 * Class JobScheduler
 *
 * Handles job scheduling, cancelling, tracking.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs
 */
class JobScheduler implements JobSchedulerInterface
{
    /**
     * Implementation of the job repository.
     *
     * @var JobRepositoryInterface
     */
    protected $jobs;

    /**
     * Construct an instance of a JobScheduler.
     *
     * @param JobRepositoryInterface $jobs
     */
    public function __construct(JobRepositoryInterface $jobs)
    {
        $this->jobs = $jobs;
    }

    /**
     * Schedule a job to run after a specific time in the future.
     *
     * @param Job $job
     * @param Carbon $runAt
     */
    public function push(Job $job, Carbon $runAt)
    {
        if ($runAt->lte(Carbon::now())) {
            throw new InvalidArgumentException(
                'The provided execution time is in the past'
            );
        }

        $job->schedulable = true;
        $job->run_at = $runAt;
        $job->state = JobState::SCHEDULED;

        $job->save();
    }

    /**
     * Get jobs that have been scheduled an ready to run.
     *
     * @param int $take
     *
     * @return Collection
     */
    public function findReady($take = 20)
    {
        $now = Carbon::now();

        return Job::query()
            ->where('state', JobState::SCHEDULED)
            ->where('run_at', '<', $now)
            ->where(function (Builder $query) use ($now) {
                $query->where('expires_at', '>', $now)
                    ->orWhereNull('expires_at');
            })
            ->orderBy('run_at', 'asc')
            ->take($take)
            ->get();
    }

    /**
     * Cancel a job.
     *
     * Note: If the job was pushed on a queue, it won't be removed. However,
     * workers should query the DB to check if it has been cancelled before
     * running them.
     *
     * @param Job $job
     */
    public function cancel(Job $job)
    {
        $job->state = JobState::CANCELLED;

        $job->save();
    }
}
