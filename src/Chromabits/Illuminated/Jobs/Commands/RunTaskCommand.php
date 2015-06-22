<?php

namespace Chromabits\Illuminated\Jobs\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\Jobs\Job as LaravelJob;
use Chromabits\Illuminated\Jobs\Exceptions\UnresolvableException;
use Chromabits\Illuminated\Jobs\Interfaces\HandlerResolverInterface;
use Chromabits\Illuminated\Jobs\Interfaces\JobRepositoryInterface;

/**
 * Class RunTaskCommand
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs\Commands
 */
class RunTaskCommand extends Command implements SelfHandling, ShouldBeQueued
{
    /**
     * Implementation of the job repository.
     *
     * @var JobRepositoryInterface
     */
    protected $jobs;

    /**
     * Implementation of the task handler resolver.
     *
     * @var HandlerResolverInterface
     */
    protected $resolver;

    /**
     * Construct an instance of a RunTaskCommand.
     *
     * Note: Parent constructor call is explicitly avoided.
     *
     * @param JobRepositoryInterface $jobs
     * @param HandlerResolverInterface $resolver
     */
    public function __construct(
        JobRepositoryInterface $jobs,
        HandlerResolverInterface $resolver
    ) {
        $this->jobs = $jobs;
        $this->resolver = $resolver;
    }

    /**
     * Handle the queue job.
     *
     * @param LaravelJob $laravelJob
     * @param $data
     */
    public function fire(LaravelJob $laravelJob, $data)
    {
        $job = null;

        try {
            $job = $this->jobs->find($data['job_id']);

            // Check if the job is valid (not expired and ready). This might
            // happen on some timing edge cases.
            if (!$job->ready()) {
                $laravelJob->delete();

                return;
            }

            // Look for a task handler
            $handler = $this->resolver->resolve($job);

            // Execute the handler
            $this->jobs->started($job, "Task handler started.\n");
            $handler->fire($job);

            $this->jobs->complete($job);
        } catch (ModelNotFoundException $e) {
            // Here we just cancel the queue job since there is no point in
            // retrying.
            $laravelJob->delete();
        } catch (UnresolvableException $e) {
            // Here we just cancel the queue job since there is no point in
            // retrying.
            $laravelJob->delete();

            $this->jobs->giveUp($job, 'Task handler was not found');
        } catch (Exception $e) {
            // If we were not able to find the job, just give up.
            if (is_null($job)) {
                $laravelJob->delete();

                return;
            }

            if ($job->hasAttemptsLeft()) {
                $this->jobs->release($job);

                $laravelJob->release();

                return;
            }

            $this->jobs->fail($job, 'Exception: ' . $e->getMessage());
            $laravelJob->delete();
        }
    }
}
