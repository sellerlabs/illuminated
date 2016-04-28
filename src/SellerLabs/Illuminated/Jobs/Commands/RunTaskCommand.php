<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Jobs\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\Jobs\Job as LaravelJob;
use SellerLabs\Illuminated\Jobs\Exceptions\UnresolvableException;
use SellerLabs\Illuminated\Jobs\Interfaces\HandlerResolverInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobRepositoryInterface;
use SellerLabs\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use SellerLabs\Nucleus\Meditation\Spec;

/**
 * Class RunTaskCommand.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs\Commands
 */
class RunTaskCommand extends Command implements SelfHandling, ShouldQueue
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
     * Implementation of the job scheduler.
     *
     * @var JobSchedulerInterface
     */
    protected $scheduler;

    /**
     * Construct an instance of a RunTaskCommand.
     *
     * Note: Parent constructor call is explicitly avoided.
     *
     * @param JobRepositoryInterface $jobs
     * @param HandlerResolverInterface $resolver
     * @param JobSchedulerInterface $scheduler
     */
    public function __construct(
        JobRepositoryInterface $jobs,
        HandlerResolverInterface $resolver,
        JobSchedulerInterface $scheduler
    ) {
        $this->jobs = $jobs;
        $this->resolver = $resolver;
        $this->scheduler = $scheduler;
    }

    /**
     * Handle the queue job.
     *
     * @param LaravelJob $laravelJob
     * @param mixed $data
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

            if ($handler->getSpec() instanceof Spec) {
                $result = $handler->getSpec()->check($job->getData());

                if ($result->failed()) {
                    $laravelJob->delete();

                    $this->jobs->giveUp($job, 'Task data does not pass Spec.');

                    return;
                }
            }

            // Execute the handler
            $this->jobs->started($job, "Task handler started.\n");
            $handler->fire($job, $this->scheduler);

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
