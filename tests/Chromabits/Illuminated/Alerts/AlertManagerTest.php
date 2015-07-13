<?php

/**
 * Copyright 2015, Eduardo Trujillo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Laravel Helpers package
 */

namespace Tests\Chromabits\Illuminated\Alerts;

use Chromabits\Illuminated\Alerts\AlertManager;
use Chromabits\Nucleus\Testing\Traits\ConstructorTesterTrait;
use Tests\Chromabits\Support\HelpersTestCase;

/**
 * Class AlertManagerTestInternal
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Alerts
 */
class AlertManagerTestInternal extends HelpersTestCase
{
    use ConstructorTesterTrait;
    use MocksViewsTrait;

    protected $constructorTypes = [
        'Chromabits\Illuminated\Alerts\AlertManager',
        'Chromabits\Illuminated\Contracts\Alerts\AlertManager',
    ];

    protected function make()
    {
        return new AlertManager(
            $this->app['session.store'],
            $this->createMockView()
        );
    }
}
