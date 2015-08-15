<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\Chromabits\Illuminated\Auth;

use Chromabits\Illuminated\Auth\AuthMigrationBatch;
use Chromabits\Illuminated\Database\Migrations\StructuredMigrator;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;

/**
 * Class AuthDatabaseTrait.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth
 */
trait AuthDatabaseTrait
{
    /**
     * @var DatabaseMigrationRepository
     */
    protected $migrationsRepository;

    /**
     * Migrate the auth database for testing.
     */
    public function migrateAuthDatabase()
    {
        $this->migrationsRepository = $this->app->make('migration.repository');
        $this->migrationsRepository->createRepository();

        $migrator = new StructuredMigrator(
            $this->migrationsRepository,
            $this->app->make('db'),
            new AuthMigrationBatch()
        );

        $migrator->run();
    }
}
