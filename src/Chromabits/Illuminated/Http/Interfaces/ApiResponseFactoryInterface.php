<?php

namespace Chromabits\Illuminated\Http\Interfaces;

use Chromabits\Illuminated\Http\ApiResponse;
use Chromabits\Nucleus\Exceptions\LackOfCoffeeException;
use Chromabits\Nucleus\Meditation\Interfaces\CheckResultInterface;

/**
 * Interface ApiResponseFactoryInterface
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
     * @return ApiResponse
     * @throws LackOfCoffeeException
     */
    public static function fromCheckable(CheckResultInterface $result);
}