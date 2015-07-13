<?php

/**
 * Copyright 2015, Eduardo Trujillo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Laravel Helpers package
 */

namespace Chromabits\Illuminated\Jobs\Tasks;

use Carbon\Carbon;
use Chromabits\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use Chromabits\Illuminated\Jobs\Job;
use Chromabits\Illuminated\Jobs\JobState;
use Chromabits\Nucleus\Support\PrimitiveType;

/**
 * Class GarbageCollectTask
 *
 * Performs garbage collection tasks for the jobs table to make sure everything
 * runs smoothly over time:
 *
 * - Remove stale jobs (configurable through the days parameter)
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs\Tasks
 */
class GarbageCollectTask extends BaseTask
{
    /**
     * Description of the task.
     *
     * @var string
     */
    protected $description = 'Perform garbage collection tasks to keep the'
    . ' job service working smoothly.';

    /**
     * Process a job.
     *
     * Note: A handler implementation does not need to worry about exception
     * handling and retries. All this is automatically managed by the task
     * runner.
     *
     * @param Job $job
     * @param JobSchedulerInterface $scheduler
     */
    public function fire(Job $job, JobSchedulerInterface $scheduler)
    {
        $jobs = Job::query()
            ->whereIn(
                'state',
                [
                    JobState::FAILED,
                    JobState::COMPLETE,
                    JobState::CANCELLED,
                ]
            );

        $repeatIn = $job->get('repeatIn', -1);

        // How far back to look
        $days = $job->get('days', 30);
        $jobs->where('created_at', '<', Carbon::now()->subDays($days));

        $job->append('Removing jobs from ' . $days . ' days ago');

        $total = $jobs->count();
        $processed = 0;

        $jobs->chunk(25, function ($jobs) use ($processed, $total, $job) {
            $processed += count($jobs);

            foreach ($jobs as $staleJob) {
                $staleJob->delete();
            }

            $job->append('Progress: ' . $processed . '/' . $total);
        });

        if ($repeatIn > -1) {
            $scheduler->pushCopy(
                $job,
                Carbon::now()->addMinutes(max($repeatIn, 1)),
                Carbon::now()->addMinutes($job->get('expiresAfter', 1440))
            );
        }
    }

    /**
     * Return documentation.
     *
     * @return array
     */
    public function getReference()
    {
        return [
            'days' => 'Minimum amount days for a job to be considered stale.',
            'repeatIn' => 'Re-run gc in N minutes (-1 = Do nothing)',
            'expireAfter' => '(When repeatIn > -1) Expire the job if its not'
                . ' ran after N minutes.',
        ];
    }

    /**
     * Get data field types.
     *
     * @return array
     */
    public function getTypes()
    {
        return [
            'days' => PrimitiveType::INTEGER,
            'repeatIn' => PrimitiveType::INTEGER,
            'expireAfter' => PrimitiveType::INTEGER,
        ];
    }

    /**
     * Get default data field values.
     *
     * @return array
     */
    public function getDefaults()
    {
        return [
            'days' => 30,
            'repeatIn' => -1,
            'expireAfter' => 1440,
        ];
    }
}
