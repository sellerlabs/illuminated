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

use Chromabits\Illuminated\Database\Migrations\StructuredMigrator;
use Tests\Chromabits\Illuminated\Jobs\JobsDatabaseTrait;
use Tests\Chromabits\Support\HelpersTestCase;

/**
 * Class MigrationsTest.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs
 */
class MigrationsTest extends HelpersTestCase
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
