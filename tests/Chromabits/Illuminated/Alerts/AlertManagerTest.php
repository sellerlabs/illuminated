<?php

namespace Chromabits\Illuminated\Alerts;

use Chromabits\Nucleus\Testing\TestCase;
use Chromabits\Nucleus\Testing\Traits\ConstructorTesterTrait;
use Tests\Chromabits\Illuminated\Alerts\MocksViewsTrait;
use Tests\Chromabits\Support\LaravelTestCase;
use Illuminate\View\View;

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
        'Chromabits\Illuminated\Contracts\Alert\AlertManager'
    ];

    protected function make()
    {
        return new AlertManager($this->app['session.store'], $this->createMockView());
    }
}
