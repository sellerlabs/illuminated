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
use Chromabits\Illuminated\Jobs\JobState;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateJobsTable
 *
 * Creates the jobs table for the job management package.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs\Migrations
 */
class CreateJobsTable extends TableMigration
{
    protected $name = 'illuminated_jobs';

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

        $table->string('task');
        $table->text('data')->nullable();
        $table->string('state')->default(JobState::IDLE);
        $table->text('message')->nullable();

        $table->boolean('schedulable')->default(false);

        $table->integer('attempts')->default(0);
        $table->integer('retries')->default(0);

        $table->timestamp('started_at')->nullable();
        $table->timestamp('completed_at')->nullable();
        $table->timestamp('expires_at')->nullable();
        $table->timestamp('run_at')->nullable();

        $table->timestamps();
    }
}
