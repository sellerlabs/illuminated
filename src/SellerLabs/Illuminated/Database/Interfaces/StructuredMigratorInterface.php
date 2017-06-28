<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Database\Interfaces;

use Illuminate\Database\Connection;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;

/**
 * Interface StructuredMigratorInterface.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Database\Interfaces
 */
interface StructuredMigratorInterface
{
    /**
     * Run the outstanding migrations at a given path.
     *
     * @param bool $pretend
     *
     */
    public function run($pretend = false);

    /**
     * Run an array of migrations.
     *
     * @param  array $migrations
     * @param  bool $pretend
     *
     */
    public function runMigrationList($migrations, $pretend = false);

    /**
     * Rollback the last migration operation.
     *
     * @param  bool $pretend
     *
     * @return int
     */
    public function rollback($pretend = false);

    /**
     * Resolve a migration instance.
     *
     * @param string $name
     *
     * @return object
     */
    public function resolve($name);

    /**
     * Get the notes for the last operation.
     *
     * @return array
     */
    public function getNotes();

    /**
     * Resolve the database connection instance.
     *
     * @param string $connection
     *
     * @return Connection
     */
    public function resolveConnection($connection);

    /**
     * Set the default connection name.
     *
     * @param string $name
     *
     */
    public function setConnection($name);

    /**
     * Get the migration repository instance.
     *
     * @return MigrationRepositoryInterface
     */
    public function getRepository();

    /**
     * Determine if the migration repository exists.
     *
     * @return bool
     */
    public function repositoryExists();
}
