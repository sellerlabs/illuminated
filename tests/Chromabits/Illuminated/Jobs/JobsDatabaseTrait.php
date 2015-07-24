<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\Chromabits\Illuminated\Jobs;

use Chromabits\Illuminated\Database\Migrations\StructuredMigrator;
use Chromabits\Illuminated\Jobs\JobsMigrationBatch;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;

/**
 * Trait JobsDatabaseTrait.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Jobs
 */
trait JobsDatabaseTrait
{
    /**
     * @var DatabaseMigrationRepository
     */
    protected $migrationsRepository;

    /**
     * Migrate the jobs database for testing.
     */
    public function migrateJobsDatabase()
    {
        $this->migrationsRepository = $this->app->make('migration.repository');
        $this->migrationsRepository->createRepository();

        $migrator = new StructuredMigrator(
            $this->migrationsRepository,
            $this->app->make('db'),
            new JobsMigrationBatch()
        );

        $migrator->run();
    }
}
