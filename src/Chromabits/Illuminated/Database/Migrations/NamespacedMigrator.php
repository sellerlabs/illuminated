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

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;

/**
 * Class NamespacedMigrator
 *
 * A migrator that supports namespaces
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Migrations
 */
class NamespacedMigrator extends Migrator
{
    /**
     * Namespace containing migrations
     *
     * @var string
     */
    protected $namespace;

    /**
     * Construct an instance of a NamespacedMigrator
     *
     * @param MigrationRepositoryInterface $repository
     * @param ConnectionResolverInterface $resolver
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param string $namespace
     */
    public function __construct(
        MigrationRepositoryInterface $repository,
        Resolver $resolver,
        Filesystem $files,
        $namespace = ''
    ) {
        parent::__construct($repository, $resolver, $files);

        $this->namespace = $namespace;
    }

    /**
     * Resolve a migration instance from a file.
     *
     * @param  string $file
     *
     * @return object
     */
    public function resolve($file)
    {
        $file = implode('_', array_slice(explode('_', $file), 4));

        $class = studly_case($file);

        // Here we append the migrations namespace if it was
        // setup in the class constructor
        if (!is_null($this->namespace)) {
            $class = trim($this->namespace, "\\") . '\\' . $class;
        }

        return new $class();
    }
}
