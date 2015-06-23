<?php

namespace Tests\Chromabits\Illuminated\Database;

use Chromabits\Illuminated\Database\StructuredMigrationServiceProvider;
use Chromabits\Illuminated\Testing\ServiceProviderTestCase;
use Illuminate\Foundation\Application;

/**
 * Class StructuredMigrationServiceProviderTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Database
 */
class StructuredMigrationServiceProviderTest extends ServiceProviderTestCase
{
    /**
     * Make an instance of the service provider being tested
     *
     * @param Application $app
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function make(Application $app)
    {
        return new StructuredMigrationServiceProvider($app);
    }
}
