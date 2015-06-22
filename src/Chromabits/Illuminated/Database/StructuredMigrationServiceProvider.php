<?php

namespace Chromabits\Illuminated\Database;

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
        $this->app->bind(
            StructuredMigratorInterface::class,
            StructuredMigrator::class
        );
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
