<?php

namespace Chromabits\Illuminated\Auth\Migrations;

use Chromabits\Illuminated\Database\Migrations\TableMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateKeyPairTable
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
        $table->text('data')->default('{}');

        $table->timestamps();
    }
}
