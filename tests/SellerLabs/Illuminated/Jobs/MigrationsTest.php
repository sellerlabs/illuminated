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

use SellerLabs\Illuminated\Database\Migrations\StructuredMigrator;
use Tests\SellerLabs\Illuminated\Jobs\JobsDatabaseTrait;
use Tests\SellerLabs\Support\IlluminatedTestCase;

/**
 * Class MigrationsTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs
 */
class MigrationsTest extends IlluminatedTestCase
{
    use JobsDatabaseTrait;

    public function testDown()
    {
        $this->migrateJobsDatabase();

        $migrator = new StructuredMigrator(
            $this->migrationsRepository,
            $this->app->make('db'),
            new JobsMigrationBatch()
        );

        $migrator->rollback();
    }
}
