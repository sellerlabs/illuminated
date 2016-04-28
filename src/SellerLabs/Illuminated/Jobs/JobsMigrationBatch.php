<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Jobs;

use SellerLabs\Illuminated\Database\Migrations\Batch;
use SellerLabs\Illuminated\Jobs\Migrations\AddQueueColumns;
use SellerLabs\Illuminated\Jobs\Migrations\CreateJobsTable;
use SellerLabs\Illuminated\Jobs\Migrations\CreateJobTagsTable;

/**
 * Class JobsMigrationBatch.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs
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
