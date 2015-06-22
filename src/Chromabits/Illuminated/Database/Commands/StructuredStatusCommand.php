<?php

namespace Chromabits\Illuminated\Database\Commands;

use Chromabits\Illuminated\Database\Interfaces\StructuredMigratorInterface;
use Chromabits\Illuminated\Database\Migrations\Batch;
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
     * The migrator instance.
     *
     * @var StructuredMigratorInterface
     */
    protected $migrator;

    /**
     * @var Batch
     */
    protected $batch;

    /**
     * Construct an instance of a StatusCommand
     *
     * @param StructuredMigratorInterface $migrator
     * @param Batch $batch
     */
    public function __construct(
        StructuredMigratorInterface $migrator,
        Batch $batch
    ) {
        parent::__construct();

        $this->migrator = $migrator;
        $this->batch = $batch;
    }

    /**
     * Execute the console command.
     */
    public function fire()
    {
        if (!$this->migrator->repositoryExists()) {
            $this->error('No migrations found.');

            return;
        }

        $ran = $this->migrator->getRepository()->getRan();

        $migrations = [];

        foreach ($this->batch->getExpanded() as $migration) {
            if (in_array($migration, $ran)) {
                $migrations[] = ['<info>✔</info>', $migration];
            } else {
                $migrations[] = ['<fg=red>✗</fg=red>', $migration];
            }

        }

        if (count($migrations) > 0) {
            $this->table(['Ran?', 'Migration'], $migrations);
        } else {
            $this->error('No migrations found');
        }
    }
}
