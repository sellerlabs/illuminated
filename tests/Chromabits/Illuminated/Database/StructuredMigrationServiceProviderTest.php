<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\Chromabits\Illuminated\Database;

use Chromabits\Illuminated\Database\Interfaces\StructuredMigratorInterface;
use Chromabits\Illuminated\Database\Interfaces\StructuredStatusInterface;
use Chromabits\Illuminated\Database\StructuredMigrationServiceProvider;
use Chromabits\Illuminated\Testing\ServiceProviderTestCase;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Class StructuredMigrationServiceProviderTest.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Database
 */
class StructuredMigrationServiceProviderTest extends ServiceProviderTestCase
{
    protected $shouldBeBound = [
        StructuredMigratorInterface::class,
        StructuredStatusInterface::class,
    ];

    /**
     * Make an instance of the service provider being tested.
     *
     * @param Application $app
     *
     * @return ServiceProvider
     */
    public function make(Application $app)
    {
        return new StructuredMigrationServiceProvider($app);
    }
}
