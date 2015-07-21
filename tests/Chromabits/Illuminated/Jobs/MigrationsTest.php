<?php

namespace Chromabits\Illuminated\Jobs;

use Chromabits\Illuminated\Database\Migrations\StructuredMigrator;
use Tests\Chromabits\Illuminated\Jobs\JobsDatabaseTrait;
use Tests\Chromabits\Support\HelpersTestCase;

/**
 * Class MigrationsTest
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
