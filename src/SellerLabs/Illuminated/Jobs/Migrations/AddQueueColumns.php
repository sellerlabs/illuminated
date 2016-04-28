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
use SellerLabs\Illuminated\Database\Migrations\BaseMigration;
use SellerLabs\Illuminated\Jobs\Job;

/**
 * Class AddQueueColumns.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs\Migrations
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
