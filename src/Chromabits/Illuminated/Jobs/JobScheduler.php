<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Jobs;

use Carbon\Carbon;
use Chromabits\Illuminated\Jobs\Interfaces\JobFactoryInterface;
use Chromabits\Illuminated\Jobs\Interfaces\JobRepositoryInterface;
use Chromabits\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use Chromabits\Nucleus\Foundation\BaseObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

/**
 * Class JobScheduler.
 *
 * Handles job scheduling, cancelling, tracking.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs
 */
class JobScheduler extends BaseObject implements JobSchedulerInterface
{
    /**
     * Implementation of the job repository.
     *
     * @var JobRepositoryInterface
     */
    protected $jobs;

    /**
     * Implementation of the job factory.
     *
     * @var JobFactoryInterface
     */
    protected $factory;

    /**
     * Construct an instance of a JobScheduler.
     *
     * @param JobRepositoryInterface $jobs
     * @param JobFactoryInterface $factory
     */
    public function __construct(
        JobRepositoryInterface $jobs,
        JobFactoryInterface $factory
    ) {
        parent::__construct();

        $this->jobs = $jobs;
        $this->factory = $factory;
    }

    /**
     * Schedule a job to run after a specific time in the future.
     *
     * @param Job $job
     * @param Carbon $runAt
     *
     * @return Job
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

        return $job;
    }

    /**
     * Apply a tag to an existing job.
     *
     * @param Job $job
     * @param string $tag
     *
     * @return JobTag|null
     */
    public function tag(Job $job, $tag)
    {
        $existing = JobTag::query()
            ->where([
                'job_id' => $job->id,
                'name' => $tag,
            ])
            ->first();

        if (is_null($existing)) {
            $newTag = new JobTag();

            $newTag->job_id = $job->id;
            $newTag->name = $tag;

            $newTag->save();

            return $newTag;
        }

        return $existing;
    }

    /**
     * Find jobs by a tag.
     *
     * @param string $tag
     * @param bool $activeOnly
     * @param int $take
     *
     * @return mixed
     */
    public function findByTag($tag, $activeOnly = true, $take = 20)
    {
        $jobsTable = Job::resolveTable();
        $jobTagsTable = JobTag::resolveTable();

        $query = Job::query()
            ->leftJoin(
                $jobTagsTable->getName(),
                $jobsTable->field('id'),
                '=',
                $jobTagsTable->field('job_id')
            )
            ->where($jobTagsTable->field('name'), $tag)
            ->take($take);

        if ($activeOnly) {
            $query->whereIn($jobsTable->field('state'), [
                JobState::SCHEDULED,
                JobState::RUNNING,
                JobState::QUEUED,
            ]);
        }

        return $query->get([$jobsTable->getName() . '.*']);
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
    public function pushCopy(
        Job $baseJob,
        Carbon $runAt,
        Carbon $expiresAt
    ) {
        $job = $this->factory->duplicate($baseJob);

        $job->expires_at = $expiresAt;

        return $this->push($job, $runAt);
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
