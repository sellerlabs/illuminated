<?php

namespace Chromabits\Illuminated\Database\Console\Migrations;

use Illuminate\Console\Command;

/**
 * Class BaseCommand
 *
 * Base migration command with support for configurable
 * migration repository path
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Console\Migrations
 */
abstract class BaseCommand extends Command
{
    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        // Here we will use a different a different migrations path
        // if the user specifies it. This allows us to put migrations
        // together with the rest of the source code
        if ($this->laravel['config']->has('database.migrator.path')) {
            return $this->laravel['config']->get('database.migrator.path');
        }

        return $this->laravel['path.database'] . '/migrations';
    }
}
