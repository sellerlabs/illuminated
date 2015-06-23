<?php

namespace Chromabits\Illuminated\Database\Migrations;

use Chromabits\Illuminated\Database\Interfaces\StructuredStatusInterface;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;

/**
 * Class StructuredStatus
 *
 * Provides status information about migrations in general. Specifically which
 * ones have been ran, are defined, need to be ran, and are unknown to the
 * application. Additionally, it is possible to get a full map of name to
 * class implementation for display purposes.
 *
 * This class is intended to be used on web UIs or CLI to show an overview of
 * migrations of an application.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Migrations
 */
class StructuredStatus implements StructuredStatusInterface
{
    /**
     * Implementation of the migrations repository.
     *
     * @var MigrationRepositoryInterface
     */
    protected $repository;

    /**
     * Current application root batch.
     *
     * @var Batch
     */
    protected $batch;

    /**
     * Construct an instance of a StructuredStatus.
     *
     * @param MigrationRepositoryInterface $repository
     * @param Batch $batch
     */
    public function __construct(
        MigrationRepositoryInterface $repository,
        Batch $batch
    ) {
        $this->repository = $repository;
        $this->batch = $batch;
    }

    /**
     * Generate a report of the status of migrations.
     *
     * @return StatusReport
     */
    public function generateReport()
    {
        $migrations = $this->batch->getExpanded();
        $ran = [];
        if ($this->repository->repositoryExists()) {
            $ran = $this->repository->getRan();
        }

        return new StatusReport($migrations, $ran);
    }

    /**
     * Get a complete mapping of aliases to migration implementations.
     *
     * @return array
     */
    public function getResolvedMap()
    {
        $map = [];
        $migrations = $this->batch->getExpanded();

        foreach ($migrations as $migration) {
            $map[$migration] = $this->batch->resolve($migration);
        }

        return $map;
    }
}
