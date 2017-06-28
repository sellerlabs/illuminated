<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Database\Console\Migrations;

use Illuminate\Database\Migrations\Migrator;

/**
 * Class StatusCommand.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Database\Console\Migrations
 */
class StatusCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'migrate:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a list of migrations up/down';

    /**
     * The migrator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * Construct an instance of a StatusCommand.
     *
     * @param Migrator $migrator
     */
    public function __construct(Migrator $migrator)
    {
        parent::__construct();

        $this->migrator = $migrator;
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

        foreach ($this->getAllMigrationFiles() as $migration) {
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

    /**
     * Get all of the migration files.
     *
     * @return array
     */
    protected function getAllMigrationFiles()
    {
        return $this->migrator->getMigrationFiles($this->getMigrationPath());
    }
}
