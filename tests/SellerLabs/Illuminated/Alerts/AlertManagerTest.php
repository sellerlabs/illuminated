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

use SellerLabs\Illuminated\Alerts\AlertManager;
use SellerLabs\Nucleus\Testing\Traits\ConstructorTesterTrait;
use Tests\SellerLabs\Support\IlluminatedTestCase;

/**
 * Class AlertManagerTestInternal.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Alerts
 */
class AlertManagerTestInternal extends IlluminatedTestCase
{
    use ConstructorTesterTrait;
    use MocksViewsTrait;

    protected $constructorTypes = [
        'SellerLabs\Illuminated\Alerts\AlertManager',
        'SellerLabs\Illuminated\Contracts\Alerts\AlertManager',
    ];

    /**
     * Make an instance of an AlertManager.
     *
     * @return AlertManager
     */
    protected function make()
    {
        return new AlertManager(
            $this->app['session.store'],
            $this->createMockView()
        );
    }
}
