<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Auth;

use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use SellerLabs\Illuminated\Auth\AuthMigrationBatch;
use SellerLabs\Illuminated\Database\Migrations\StructuredMigrator;

/**
 * Class AuthDatabaseTrait.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Auth
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
