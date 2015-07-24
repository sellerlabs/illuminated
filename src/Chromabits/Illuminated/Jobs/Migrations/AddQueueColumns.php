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

use Chromabits\Illuminated\Database\Migrations\BaseMigration;
use Chromabits\Illuminated\Jobs\Job;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddQueueColumns.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs\Migrations
 */
class AddQueueColumns extends BaseMigration
{
    /**
     * Run migrations up.
     *
     * @return mixed
     */
    public function up()
    {
        $this->builder->table(
            Job::resolveTable()->getName(),
            function (Blueprint $table) {
                $table->string('queue_connection')->nullable();
                $table->string('queue_name')->nullable();
            }
        );
    }

    /**
     * Rollback changes performed by this migration.
     *
     * @return mixed
     */
    public function down()
    {
        $this->builder->table(
            Job::resolveTable()->getName(),
            function (Blueprint $table) {
                if (!$this->isSqlite()) {
                    $table->dropColumn(['queue_connection', 'queue_name']);
                }
            }
        );
    }
}
