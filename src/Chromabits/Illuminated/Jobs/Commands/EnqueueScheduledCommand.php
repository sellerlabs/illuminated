<?php

/**
 * Copyright 2015, Eduardo Trujillo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Laravel Helpers package
 */

namespace Chromabits\Illuminated\Jobs\Commands;

use Chromabits\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\QueueManager;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class EnqueueScheduledCommand
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
     * Implementation of the queue manager.
     *
     * @var QueueManager
     */
    protected $queue;

    /**
     * Construct an instance of a EnqueueScheduledCommand.
     *
     * @param JobSchedulerInterface $scheduler
     * @param QueueManager $queue
     */
    public function __construct(
        JobSchedulerInterface $scheduler,
        QueueManager $queue
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
        $this->queue = $queue;
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

        foreach ($ready as $job) {
            $this->queue->push(RunTaskCommand::class, [
                'job_id' => $job->id,
            ]);

            $this->line('Queued Job ID: ' . $job->id . ' ' . $job->task);
        }

        $this->line('Finished.');
    }
}
