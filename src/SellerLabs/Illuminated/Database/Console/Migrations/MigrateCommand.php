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

use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MigrateCommand.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Database\Console\Migrations
 */
class MigrateCommand extends BaseCommand
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database migrations';

    /**
     * The migrator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * Construct an instance of a MigrateCommand.
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
     *
     */
    public function fire()
    {
        if (!$this->confirmToProceed()) {
            return;
        }

        $this->prepareDatabase();

        // The pretend option can be used for "simulating" the migration and
        // grabbing the SQL queries that would fire if the migration were to be
        // run against a database for real, which is helpful for double checking
        // migrations.
        $pretend = $this->input->getOption('pretend');

        $path = $this->getMigrationPath();

        $this->migrator->run($path, $pretend);

        // Once the migrator has run we will grab the note output and send it
        // out to the console screen, since the migrator itself functions
        // without having any instances of the OutputInterface contract passed
        // into the class.
        foreach ($this->migrator->getNotes() as $note) {
            $this->output->writeln($note);
        }

        // Finally, if the "seed" option has been given, we will re-run the
        // database seed task to re-populate the database, which is convenient
        // when adding a migration and a seed at the same time, as it is only
        // this command.
        if ($this->input->getOption('seed')) {
            $this->call('db:seed', ['--force' => true]);
        }
    }

    /**
     * Prepare the migration database for running.
     *
     */
    protected function prepareDatabase()
    {
        $this->migrator->setConnection($this->input->getOption('database'));

        if (!$this->migrator->repositoryExists()) {
            $options = ['--database' => $this->input->getOption('database')];

            $this->call('migrate:install', $options);
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [
                'database',
                null,
                InputOption::VALUE_OPTIONAL, 'The database connection to use.',
            ],
            [
                'force',
                null,
                InputOption::VALUE_NONE,
                'Force the operation to run when in production.',
            ],
            [
                'pretend',
                null,
                InputOption::VALUE_NONE,
                'Dump the SQL queries that would be run.',
            ],
            [
                'seed',
                null,
                InputOption::VALUE_NONE,
                'Indicates if the seed task should be re-run.',
            ],
        ];
    }
}
