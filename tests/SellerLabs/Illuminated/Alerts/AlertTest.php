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

use SellerLabs\Illuminated\Alerts\Alert;
use SellerLabs\Nucleus\Testing\TestCase;
use SellerLabs\Nucleus\Testing\Traits\ConstructorTesterTrait;

/**
 * Class AlertTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Alerts
 */
class AlertTest extends TestCase
{
    use ConstructorTesterTrait;

    protected $constructorTypes = [
        'SellerLabs\Illuminated\Alerts\Alert',
    ];

    /**
     * Make an instance of an Alert.
     *
     * @return Alert
     */
    protected function make()
    {
        return new Alert();
    }
}
