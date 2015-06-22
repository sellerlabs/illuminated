<?php

namespace Chromabits\Illuminated\Jobs\Tasks;

use Carbon\Carbon;
use Chromabits\Nucleus\Support\PrimitiveType;
use Chromabits\Illuminated\Jobs\JobState;
use Chromabits\Illuminated\Jobs\Job;

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
     */
    public function fire(Job $job)
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

        $now = Carbon::now();

        // How far back to look
        $days = $job->get('days', 30);
        $jobs->where('created_at', '<', $now->subDays($days));

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
    }

    /**
     * Return documentation.
     *
     * @return array
     */
    public function getReference()
    {
        return [
            'days' => 'Integer. Minimum amount days for a job to be considered'
                . ' stale',
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
            'days' => PrimitiveType::STRING,
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
        ];
    }
}
