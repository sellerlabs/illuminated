<?php

namespace Chromabits\Illuminated\Database;

use Chromabits\Illuminated\Database\Commands\StructuredInstallCommand;
use Chromabits\Illuminated\Database\Commands\StructuredMigrateCommand;
use Chromabits\Illuminated\Database\Commands\StructuredStatusCommand;
use Chromabits\Illuminated\Database\Interfaces\StructuredMigratorInterface;
use Chromabits\Illuminated\Database\Migrations\Batch;
use Chromabits\Illuminated\Database\Migrations\StructuredMigrator;
use Illuminate\Contracts\Foundation\Application;
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
        $this->app->bind(
            StructuredMigratorInterface::class,
            function (Application $app) {
                return new StructuredMigrator(
                    $app->make('migration.repository'),
                    $app->make('db'),
                    $app->make(Batch::class)
                );
            }
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
