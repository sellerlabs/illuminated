<?php

namespace Chromabits\Illuminated\Auth;

use Chromabits\Illuminated\Auth\Migrations\CreateKeyPairTable;
use Chromabits\Illuminated\Database\Migrations\Batch;

/**
 * Class AuthMigrationBatch
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth
 */
class AuthMigrationBatch extends Batch
{
    /**
     * Return a string array of the class names of migrations to run.
     *
     * - Order matters: Output migrations in the order they should happen.
     * - Values can be either the name of a migration class or an alias.
     *  See getAliases().
     *
     * @return string[]
     */
    public function getMigrations()
    {
        return [
            CreateKeyPairTable::class,
        ];
    }
}
