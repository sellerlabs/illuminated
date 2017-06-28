<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Database;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use SellerLabs\Illuminated\Database\Interfaces\StructuredMigratorInterface;
use SellerLabs\Illuminated\Database\Interfaces\StructuredStatusInterface;
use SellerLabs\Illuminated\Database\StructuredMigrationServiceProvider;
use SellerLabs\Illuminated\Testing\ServiceProviderTestCase;

/**
 * Class StructuredMigrationServiceProviderTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Database
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
