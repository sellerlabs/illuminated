<?php

namespace Chromabits\Illuminated\Database\Commands;

use Chromabits\Illuminated\Database\Interfaces\StructuredStatusInterface;
use Illuminate\Console\Command;

/**
 * Class StructuredStatusCommand
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
     * Construct an instance of a StatusCommand
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
            $rows[] = ['<warning>☁︎ Unknown</warning>', $migration];
        }

        if (count($rows) > 0) {
            $this->table(['Status', 'Migration'], $rows);
        } else {
            $this->error('No migrations found');
        }
    }
}
