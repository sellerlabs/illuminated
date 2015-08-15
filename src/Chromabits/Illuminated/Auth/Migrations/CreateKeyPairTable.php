<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Auth\Migrations;

use Chromabits\Illuminated\Database\Migrations\TableMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateKeyPairTable.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth\Migrations
 */
class CreateKeyPairTable extends TableMigration
{
    protected $name = 'illuminated_key_pairs';

    /**
     * Create the table schema.
     *
     * @param Blueprint $table
     *
     * @return mixed
     */
    protected function create(Blueprint $table)
    {
        $table->increments('id');

        $table->text('public_id');
        $table->text('secret_key');
        $table->string('type');
        $table->text('data');

        $table->timestamps();
    }
}
