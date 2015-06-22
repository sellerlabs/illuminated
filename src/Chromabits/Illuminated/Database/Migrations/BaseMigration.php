<?php

namespace Chromabits\Illuminated\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Builder;

/**
 * Class BaseMigration
 *
 * @author Benjamin Kovach <benjamin@roundsphere.com>
 * @package Chromabits\Illuminated\Database\Migrations
 */
class BaseMigration extends Migration
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Construct an instance of a BaseMigration
     */
    public function __construct()
    {
        $this->builder = app('db')->connection($this->connection)
            ->getSchemaBuilder();
    }
}
