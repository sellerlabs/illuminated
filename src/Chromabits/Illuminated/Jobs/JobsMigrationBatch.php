<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Jobs;

use Chromabits\Illuminated\Database\Migrations\Batch;
use Chromabits\Illuminated\Jobs\Migrations\AddQueueColumns;
use Chromabits\Illuminated\Jobs\Migrations\CreateJobsTable;
use Chromabits\Illuminated\Jobs\Migrations\CreateJobTagsTable;

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
            CreateJobTagsTable::class,
            AddQueueColumns::class,
        ];
    }
}
