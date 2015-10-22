<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Database\Migrations;

use Exception;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class TableMigration.
 *
 * A base migration for migrations that simply create/drop a table. Applications
 * usually have one or more of this kind. This base class simply reduces the
 * amount of repeated code and allows the table name to be defined in
 * declarative way.
 *
 * If you need to modify columns or perform other operations, please consider
 * using a different base migration class.
 *
 * To use this migration class:
 * - Extend it.
 * - Set the name of the table by overriding the `$name` property.
 * - Implement the `create(Blueprint $table)` method.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Migrations
 */
abstract class TableMigration extends BaseMigration
{
    /**
     * Name of the table.
     *
     * @var string
     */
    protected $name = null;

    /**
     * Create the table schema.
     *
     * @param Blueprint $table
     */
    abstract protected function create(Blueprint $table);

    /**
     * Perform some sanity checks on the migration.
     *
     * @throws Exception
     */
    final protected function validate()
    {
        if ($this->name === null) {
            throw new Exception(
                'Invalid table name in migration: ' . static::class
            );
        }
    }

    /**
     * Run the migration.
     *
     * @throws Exception
     */
    final public function up()
    {
        $this->validate();

        $this->builder->create($this->name, function (Blueprint $table) {
            $this->create($table);
        });
    }

    /**
     * Rollback the migration.
     *
     * @throws Exception
     */
    final public function down()
    {
        $this->validate();

        $this->builder->drop($this->name);
    }
}
