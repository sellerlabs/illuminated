<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Jobs\Migrations;

use Illuminate\Database\Schema\Blueprint;
use SellerLabs\Illuminated\Database\Migrations\TableMigration;

/**
 * Class CreateJobTagsTable.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs\Migrations
 */
class CreateJobTagsTable extends TableMigration
{
    protected $name = 'illuminated_job_tags';

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

        $table->unsignedInteger('job_id');
        $table->string('name');

        $table->unique(['job_id', 'name']);

        $table->timestamps();
    }
}
