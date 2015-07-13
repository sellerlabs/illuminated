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
