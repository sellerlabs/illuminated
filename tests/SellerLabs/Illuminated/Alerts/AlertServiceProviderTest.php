<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Alerts;

use SellerLabs\Illuminated\Alerts\AlertServiceProvider;
use Tests\SellerLabs\Support\IlluminatedTestCase;

/**
 * Class AlertServiceProviderTestInternal.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Alerts
 */
class AlertServiceProviderTestInternal extends IlluminatedTestCase
{
    public function testRegister()
    {
        $provider = new AlertServiceProvider($this->app);

        $provider->register();

        $this->assertTrue(
            $this->app->bound(
                'SellerLabs\Illuminated\Contracts\Alerts\AlertManager'
            )
        );
    }

    public function testProvides()
    {
        $provider = new AlertServiceProvider($this->app);

        $this->assertInternalType('array', $provider->provides());
    }
}
