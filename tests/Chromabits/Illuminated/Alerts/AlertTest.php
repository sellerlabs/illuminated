<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\Chromabits\Illuminated\Alerts;

use Chromabits\Illuminated\Alerts\Alert;
use Chromabits\Nucleus\Testing\TestCase;
use Chromabits\Nucleus\Testing\Traits\ConstructorTesterTrait;

/**
 * Class AlertTest.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Alerts
 */
class AlertTest extends TestCase
{
    use ConstructorTesterTrait;

    protected $constructorTypes = [
        'Chromabits\Illuminated\Alerts\Alert',
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
