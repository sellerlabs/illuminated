<?php

namespace Tests\Chromabits\Illuminated\Alerts;

use Chromabits\Illuminated\Alerts\AlertManager;
use Chromabits\Nucleus\Testing\Traits\ConstructorTesterTrait;
use Tests\Chromabits\Support\LaravelTestCase;

/**
 * Class AlertManagerTest
 *
 * @package Chromabits\Illuminated\Alerts
 */
class AlertManagerTest extends LaravelTestCase
{
    use ConstructorTesterTrait;
    use MocksViewsTrait;

    protected $constructorTypes = [
        'Chromabits\Illuminated\Alerts\AlertManager',
        'Chromabits\Illuminated\Contracts\Alerts\AlertManager'
    ];

    protected function make()
    {
        return new AlertManager($this->app['session.store'], $this->createMockView());
    }
}
