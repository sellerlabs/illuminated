<?php

namespace Chromabits\Illuminated\Alerts;

use Chromabits\Nucleus\Testing\TestCase;
use Chromabits\Nucleus\Testing\Traits\ConstructorTesterTrait;

/**
 * Class AlertTest
 *
 * @package Chromabits\Illuminated\Alerts
 */
class AlertTest extends TestCase
{
    use ConstructorTesterTrait;

    protected $constructorTypes = [
        'Chromabits\Illuminated\Alerts\Alert'
    ];

    protected function make()
    {
        return new Alert();
    }
}
