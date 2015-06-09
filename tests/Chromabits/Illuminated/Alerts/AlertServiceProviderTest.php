<?php

namespace Tests\Chromabits\Illuminated\Alerts;

use Chromabits\Illuminated\Alerts\AlertServiceProvider;
use Tests\Chromabits\Support\HelpersTestCase;

/**
 * Class AlertServiceProviderTestInternal
 *
 * @package Tests\Chromabits\Illuminated\Alerts
 */
class AlertServiceProviderTestInternal extends HelpersTestCase
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
