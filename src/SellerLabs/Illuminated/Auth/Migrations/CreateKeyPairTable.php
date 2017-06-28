<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Auth\Migrations;

use Illuminate\Database\Schema\Blueprint;
use SellerLabs\Illuminated\Database\Migrations\TableMigration;

/**
 * Class CreateKeyPairTable.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Auth\Migrations
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
