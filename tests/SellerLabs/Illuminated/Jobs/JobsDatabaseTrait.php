<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Jobs;

use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use SellerLabs\Illuminated\Database\Migrations\StructuredMigrator;
use SellerLabs\Illuminated\Jobs\JobsMigrationBatch;

/**
 * Trait JobsDatabaseTrait.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Jobs
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
