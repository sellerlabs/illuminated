<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Jobs\Migrations;

use Chromabits\Illuminated\Database\Migrations\TableMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateJobTagsTable
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs\Migrations
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
