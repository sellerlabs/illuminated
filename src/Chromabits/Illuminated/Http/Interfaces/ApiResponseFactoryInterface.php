<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Http\Interfaces;

use Chromabits\Illuminated\Http\ApiResponse;
use Chromabits\Nucleus\Exceptions\LackOfCoffeeException;
use Chromabits\Nucleus\Meditation\Interfaces\CheckResultInterface;

/**
 * Interface ApiResponseFactoryInterface.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Http\Interfaces
 */
interface ApiResponseFactoryInterface
{
    /**
     * Create an API validation response from a CheckResultInterface.
     *
     * @param CheckResultInterface $result
     *
     * @throws LackOfCoffeeException
     * @return ApiResponse
     */
    public function fromCheckable(CheckResultInterface $result);
}
