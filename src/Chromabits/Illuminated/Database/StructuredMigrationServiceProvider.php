<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Database;

use Chromabits\Illuminated\Database\Commands\StructuredInstallCommand;
use Chromabits\Illuminated\Database\Commands\StructuredMigrateCommand;
use Chromabits\Illuminated\Database\Commands\StructuredRollbackCommand;
use Chromabits\Illuminated\Database\Commands\StructuredStatusCommand;
use Chromabits\Illuminated\Database\Interfaces\StructuredMigratorInterface;
use Chromabits\Illuminated\Database\Interfaces\StructuredStatusInterface;
use Chromabits\Illuminated\Database\Migrations\StructuredMigrator;
use Chromabits\Illuminated\Database\Migrations\StructuredStatus;
use Chromabits\Illuminated\Support\ServiceProvider;

/**
 * Class StructuredMigrationServiceProvider.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database
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
