<?php

namespace Tests\Chromabits\Illuminated\Alerts;

use Mockery;

/**
 * Trait MocksViewsTrait
 *
 * @package Tests\Chromabits\Illuminated\Alerts\Tests
 */
trait MocksViewsTrait
{
    protected function createMockView()
    {
        $mock = Mockery::mock('Illuminate\View\View');

        return $mock;
    }
}
