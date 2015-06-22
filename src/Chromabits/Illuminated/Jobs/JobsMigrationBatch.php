<?php

namespace Chromabits\Illuminated\Jobs;

use Chromabits\Illuminated\Database\Migrations\Batch;
use Chromabits\Illuminated\Jobs\Migrations\CreateJobsTable;

/**
 * Class JobsMigrationBatch
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs
 */
class JobsMigrationBatch extends Batch
{
    /**
     * Return a string array of the class names of migrations to run.
     *
     * - Order matters: Output migrations in the order they should happen.
     * - Values can be either the name of a migration class or an alias.
     *  See getAliases().
     *
     * @return string[]
     */
    public function getMigrations()
    {
        return [
            CreateJobsTable::class,
        ];
    }
}
