<?php

namespace Chromabits\Illuminated\Database;

use Chromabits\Illuminated\Database\Migrations\NamespacedMigrator;
use Illuminate\Database\MigrationServiceProvider;

/**
 * Class NamespacedMigrationServiceProvider
 *
 * @package Chromabits\Illuminated\Database
 */
class NamespacedMigrationServiceProvider extends MigrationServiceProvider
{
    /**
     * Register the migrator service.
     *
     * @return void
     */
    protected function registerMigrator()
    {
        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.
        $this->app->singleton('migrator', function($app)
        {
            $repository = $app['migration.repository'];

            $namespace = $app['config']->get('database.migrator.namespace', '');

            return new NamespacedMigrator($repository, $app['db'], $app['files'], $namespace);
        });
    }
}
