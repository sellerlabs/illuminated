<?php

namespace Tests\Chromabits\Illuminated\Alerts;

use Chromabits\Illuminated\Alerts\AlertServiceProvider;
use Tests\Chromabits\Support\LaravelTestCase;

/**
 * Class AlertServiceProviderTest
 *
 * @package Tests\Chromabits\Illuminated\Alerts
 */
class AlertServiceProviderTest extends LaravelTestCase
{
    public function testRegister()
    {
        $provider = new AlertServiceProvider($this->app);

        $provider->register();

        $this->assertTrue($this->app->bound('Chromabits\Illuminated\Contracts\Alerts\AlertManager'));
    }

    public function testProvides()
    {
        $provider = new AlertServiceProvider($this->app);

        $this->assertInternalType('array', $provider->provides());
    }
}
