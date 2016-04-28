<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Database;

use SellerLabs\Illuminated\Database\Commands\StructuredInstallCommand;
use SellerLabs\Illuminated\Database\Commands\StructuredMigrateCommand;
use SellerLabs\Illuminated\Database\Commands\StructuredRollbackCommand;
use SellerLabs\Illuminated\Database\Commands\StructuredStatusCommand;
use SellerLabs\Illuminated\Database\Interfaces\StructuredMigratorInterface;
use SellerLabs\Illuminated\Database\Interfaces\StructuredStatusInterface;
use SellerLabs\Illuminated\Database\Migrations\StructuredMigrator;
use SellerLabs\Illuminated\Database\Migrations\StructuredStatus;
use SellerLabs\Illuminated\Support\ServiceProvider;

/**
 * Class StructuredMigrationServiceProvider.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Database
 */
class StructuredMigrationServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the service provider.
     */
    public function register()
    {
        // Here, we unfortunately have to add some bindings that are not added
        // by Laravel itself.
        $this->app->bind(
            'Illuminate\Database\Migrations\MigrationRepositoryInterface',
            'migration.repository'
        );
        $this->app->bind(
            'Illuminate\Database\ConnectionResolverInterface',
            'db'
        );

        $this->app->singleton(
            StructuredMigratorInterface::class,
            StructuredMigrator::class
        );

        $this->app->bind(
            StructuredStatusInterface::class,
            StructuredStatus::class
        );

        $this->commands([
            StructuredInstallCommand::class,
            StructuredMigrateCommand::class,
            StructuredStatusCommand::class,
            StructuredRollbackCommand::class,
        ]);
    }

    /**
     * Return a list of services provided.
     *
     * @return array
     */
    public function provides()
    {
        return [
            StructuredMigratorInterface::class,
            StructuredStatusInterface::class,
        ];
    }
}
