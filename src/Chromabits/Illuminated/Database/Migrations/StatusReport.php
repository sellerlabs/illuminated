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

/**
 * Class StatusReport
 *
 * Computes a report on the status of migrations based on which migrations are
 * known/defined and which ones have been ran.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Migrations
 */
class StatusReport
{
    /**
     * List of all migrations defined in the application.
     *
     * @var array
     */
    protected $migrations = [];

    /**
     * List of migrations that have been ran.
     *
     * @var array
     */
    protected $ran = [];

    /**
     * Migrations that are defined but not ran.
     *
     * @var array
     */
    protected $idle = [];

    /**
     * Migrations that have been ran but are not defined.
     *
     * @var array
     */
    protected $unknown = [];

    /**
     * Construct an instance of a StatusReport.
     *
     * @param $migrations
     * @param $ran
     */
    public function __construct($migrations, $ran)
    {
        $this->migrations = $migrations;
        $this->ran = $ran;

        $this->categorize();
    }

    /**
     * Categorize migrations
     */
    protected function categorize()
    {
        $this->idle = [];
        $this->unknown = [];

        // Look for pending migrations
        foreach ($this->migrations as $migration) {
            if (!in_array($migration, $this->ran)) {
                $this->idle[] = $migration;
            }
        }

        // Look for unknown migrations.
        foreach ($this->ran as $migration) {
            if (!in_array($migration, $this->migrations)) {
                $this->unknown[] = $migration;
            }
        }
    }

    /**
     * Return the name of all known migrations.
     *
     * @return array
     */
    public function getMigrations()
    {
        return $this->migrations;
    }

    /**
     * Return the name of all migrations ran.
     *
     * @return array
     */
    public function getRan()
    {
        return $this->ran;
    }

    /**
     * Get names of migrations that have not been ran yet.
     *
     * @return array
     */
    public function getIdle()
    {
        return $this->idle;
    }

    /**
     * Get names of migrations that have been ran but that are unknown to the
     * current application.
     *
     * @return array
     */
    public function getUnknown()
    {
        return $this->unknown;
    }
}
