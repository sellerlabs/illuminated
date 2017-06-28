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

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use SellerLabs\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use SellerLabs\Illuminated\Jobs\Job;
use SellerLabs\Illuminated\Jobs\JobState;
use SellerLabs\Illuminated\Queue\Interfaces\QueuePusherInterface;
use SellerLabs\Nucleus\Support\Std;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class EnqueueScheduledCommand.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs\Commands
 */
class EnqueueScheduledCommand extends Command
{
    /**
     * Name of the command.
     *
     * @var string
     */
    protected $name = 'jobs:enqueue';

    /**
     * Description of the command.
     *
     * @var string
     */
    protected $description = 'Look for pending jobs and enqueue them';

    /**
     * Implementation of the job scheduler.
     *
     * @var JobSchedulerInterface
     */
    protected $scheduler;

    /**
     * Implementation of the config repository.
     *
     * @var Repository
     */
    protected $config;

    /**
     * Implementation of the queue pusher.
     *
     * @var QueuePusherInterface
     */
    protected $pusher;

    /**
     * Construct an instance of a EnqueueScheduledCommand.
     *
     * @param JobSchedulerInterface $scheduler
     * @param QueuePusherInterface $pusher
     * @param Repository $config
     *
     * @internal param QueueManager $queue
     */
    public function __construct(
        JobSchedulerInterface $scheduler,
        QueuePusherInterface $pusher,
        Repository $config
    ) {
        parent::__construct();

        $this->addOption(
            'take',
            't',
            InputOption::VALUE_OPTIONAL,
            'Number of jobs to look for',
            25
        );

        $this->scheduler = $scheduler;
        $this->pusher = $pusher;
        $this->config = $config;
    }

    /**
     * Execute the command.
     */
    public function fire()
    {
        $take = $this->option('take');

        $ready = $this->scheduler->findReady($take);

        if (count($ready) < 1) {
            $this->line('No jobs ready to run.');

            return;
        }

        $defaultConnection = $this->config->get('jobs.queue.connection');
        $defaultQueue = $this->config->get('jobs.queue.id');

        /** @var Job $job */
        foreach ($ready as $job) {
            $this->pusher->push(
                RunTaskCommand::class,
                [
                    'job_id' => $job->id,
                ],
                Std::coalesce($job->queue_connection, $defaultConnection),
                Std::coalesce($job->queue_name, $defaultQueue)
            );

            $job = $job->fresh();

            // Sometimes, a developer might be running a sync queue. This means
            // we have to check if the jobs is still in a scheduled state.
            // Only then, we will update the status to queued. Otherwise, the
            // job gets stuck in a queued state.
            if ($job->state === JobState::SCHEDULED) {
                $job->state = JobState::QUEUED;
                $job->save();
            }

            $this->line('Queued Job ID: ' . $job->id . ' ' . $job->task);
        }

        $this->line('Finished.');
    }
}
