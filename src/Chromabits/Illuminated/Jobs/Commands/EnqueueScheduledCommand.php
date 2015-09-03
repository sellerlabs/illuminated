<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Jobs\Commands;

use Chromabits\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use Chromabits\Illuminated\Jobs\Job;
use Chromabits\Illuminated\Jobs\JobState;
use Chromabits\Illuminated\Queue\Interfaces\QueuePusherInterface;
use Chromabits\Nucleus\Support\Std;
use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Config\Repository;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class EnqueueScheduledCommand.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs\Commands
 */
class EnqueueScheduledCommand extends Command implements SelfHandling
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

            $job->state = JobState::QUEUED;
            $job->save();

            $this->line('Queued Job ID: ' . $job->id . ' ' . $job->task);
        }

        $this->line('Finished.');
    }
}
