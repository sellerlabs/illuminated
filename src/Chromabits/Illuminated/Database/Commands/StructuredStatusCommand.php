<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Database\Commands;

use Chromabits\Illuminated\Database\Interfaces\StructuredStatusInterface;
use Illuminate\Console\Command;

/**
 * Class StructuredStatusCommand.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Commands
 */
class StructuredStatusCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'structured:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a list of migrations up/down';

    /**
     * Implementation of the structured migration status service.
     *
     * @var StructuredStatusInterface
     */
    protected $status;

    /**
     * Construct an instance of a StatusCommand.
     *
     * @param StructuredStatusInterface $status
     */
    public function __construct(StructuredStatusInterface $status)
    {
        parent::__construct();

        $this->status = $status;
    }

    /**
     * Execute the console command.
     */
    public function fire()
    {
        $report = $this->status->generateReport();

        $ran = $report->getRan();

        $rows = [];

        foreach ($report->getMigrations() as $migration) {
            if (in_array($migration, $ran)) {
                $rows[] = ['<info>✔ Yes</info>', $migration];
            } else {
                $rows[] = ['<fg=red>✗ No</fg=red>', $migration];
            }
        }

        foreach ($report->getUnknown() as $migration) {
            $rows[] = ['<comment>✗ Exotic</comment>', $migration];
        }

        if (count($rows) > 0) {
            $this->table(['Status', 'Migration'], $rows);

            if (count($report->getUnknown()) > 0) {
                $this->line('<comment>Warning: Some "exotic" (unknown)'
                    . ' migrations were detected. Check your batch classes'
                    . '</comment>');
            }
        } else {
            $this->error('No migrations found');
        }
    }
}
