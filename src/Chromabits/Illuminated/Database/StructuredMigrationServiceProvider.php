<?php

namespace Chromabits\Illuminated\Database;

use Chromabits\Illuminated\Database\Commands\StructuredInstallCommand;
use Chromabits\Illuminated\Database\Commands\StructuredMigrateCommand;
use Chromabits\Illuminated\Database\Commands\StructuredStatusCommand;
use Chromabits\Illuminated\Database\Interfaces\StructuredMigratorInterface;
use Chromabits\Illuminated\Database\Migrations\StructuredMigrator;
use Illuminate\Support\ServiceProvider;

/**
 * Class StructuredMigrationServiceProvider
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database
 */
class StructuredMigrationServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
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

        $this->commands([
            StructuredInstallCommand::class,
            StructuredMigrateCommand::class,
            StructuredStatusCommand::class,
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
        ];
    }
}
