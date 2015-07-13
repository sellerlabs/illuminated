<?php

/**
 * Copyright 2015, Eduardo Trujillo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Laravel Helpers package
 */

namespace Tests\Chromabits\Illuminated\Jobs;

use Chromabits\Illuminated\Database\Migrations\StructuredMigrator;
use Chromabits\Illuminated\Jobs\JobsMigrationBatch;

/**
 * Trait JobsDatabaseTrait
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Jobs
 */
trait JobsDatabaseTrait
{
    public function migrateJobsDatabase()
    {
        $repository = $this->app->make('migration.repository');
        $repository->createRepository();

        $migrator = new StructuredMigrator(
            $repository,
            $this->app->make('db'),
            new JobsMigrationBatch()
        );

        $migrator->run();
    }
}
