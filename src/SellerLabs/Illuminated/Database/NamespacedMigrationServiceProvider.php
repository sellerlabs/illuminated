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

use Illuminate\Database\MigrationServiceProvider;
use SellerLabs\Illuminated\Database\Console\Migrations\MigrateCommand;
use SellerLabs\Illuminated\Database\Console\Migrations\MigrateMakeCommand;
use SellerLabs\Illuminated\Database\Console\Migrations\StatusCommand;
use SellerLabs\Illuminated\Database\Migrations\NamespacedMigrator;

/**
 * Class NamespacedMigrationServiceProvider.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Database
 */
class NamespacedMigrationServiceProvider extends MigrationServiceProvider
{
    /**
     * Register the migrator service.
     */
    protected function registerMigrator()
    {
        // The migrator is responsible for actually running and rollback the
        // migration files in the application. We'll pass in our database
        // connection resolver so the migrator can resolve any of these
        // connections when it needs to.
        $this->app->singleton('migrator', function ($app) {
            $repository = $app['migration.repository'];

            $namespace = $app['config']->get('database.migrator.namespace', '');

            return new NamespacedMigrator(
                $repository,
                $app['db'],
                $app['files'],
                $namespace
            );
        });
    }

    /**
     * Register the "migrate" migration command.
     */
    protected function registerMigrateCommand()
    {
        $this->app->singleton('command.migrate', function ($app) {
            return new MigrateCommand($app['migrator']);
        });
    }

    /**
     * Register the "migrate:status" migration command.
     */
    protected function registerStatusCommand()
    {
        $this->app->singleton('command.migrate.status', function ($app) {
            return new StatusCommand($app['migrator']);
        });
    }

    /**
     * Register the "make" migration command.
     */
    protected function registerMakeCommand()
    {
        $this->registerCreator();

        $this->app->singleton('command.migrate.make', function ($app) {
            // Once we have the migration creator registered, we will create
            // the command and inject the creator. The creator is responsible
            // for the actual file creation of the migrations, and may be
            // extended by these developers.
            $creator = $app['migration.creator'];

            $composer = $app['composer'];

            return new MigrateMakeCommand($creator, $composer);
        });
    }
}
