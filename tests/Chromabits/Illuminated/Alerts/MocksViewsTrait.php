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

use Mockery;
use Mockery\MockInterface;

/**
 * Trait MocksViewsTrait.
 *
 * @package Tests\Chromabits\Illuminated\Alerts\Tests
 */
trait MocksViewsTrait
{
    /**
     * Creates a view for mocking.
     *
     * @return MockInterface
     */
    protected function createMockView()
    {
        $mock = Mockery::mock('Illuminate\View\View');

        return $mock;
    }
}
