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

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class EnqueueDaemonCommand
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs\Commands
 */
class EnqueueDaemonCommand extends Command implements SelfHandling
{
    /**
     * Name of the command.
     *
     * @var string
     */
    protected $name = 'jobs:daemon';

    /**
     * Description of the command.
     *
     * @var string
     */
    protected $description = 'Continuously enqueue jobs';

    /**
     * Construct an instance of an EnqueueDaemonCommand.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addOption(
            'take',
            't',
            InputOption::VALUE_OPTIONAL,
            'Number of jobs to look for',
            25
        );

        $this->addOption(
            'sleep',
            's',
            InputOption::VALUE_OPTIONAL,
            'Minutes to sleep between enqueues',
            60
        );
    }

    /**
     * Execute the command.
     *
     * @throws \Exception
     */
    public function fire()
    {
        for (;;) {
            $this->line('Waiting...');

            sleep((int) $this->option('sleep'));

            if ($this->callAsync() === false) {
                return;
            }
        }
    }

    /**
     * Call the enqueue command in a new process.
     *
     * @throws \Exception
     */
    protected function callAsync()
    {
        $pid = pcntl_fork();

        switch ($pid) {
            case -1:
                throw new \Exception('Could not fork');
            case 0:
                // Child process
                $this->call('jobs:enqueue', [
                    '--take' => $this->option('take'),
                ]);

                return false;
            default:
                // Parent process
                $this->line('Ran enqueue');

                return true;
        }
    }
}
