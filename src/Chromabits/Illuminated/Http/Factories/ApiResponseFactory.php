<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Http\Factories;

use Chromabits\Illuminated\Http\ApiResponse;
use Chromabits\Illuminated\Http\Interfaces\ApiResponseFactoryInterface;
use Chromabits\Nucleus\Exceptions\LackOfCoffeeException;
use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Meditation\Interfaces\CheckResultInterface;

/**
 * Class ApiResponseFactory.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Http\Factories
 */
class ApiResponseFactory extends BaseObject implements
    ApiResponseFactoryInterface
{
    /**
     * Create an API validation response from a CheckResultInterface.
     *
     * @param CheckResultInterface $result
     *
     * @throws LackOfCoffeeException
     * @return ApiResponse
     */
    public function fromCheckable(CheckResultInterface $result)
    {
        if ($result->passed()) {
            throw new LackOfCoffeeException(
                'You are trying to send a validation error response,'
                . ' but your check actually passed!'
            );
        }

        return new ApiResponse([
            'missing' => $result->getMissing(),
            'validation' => $result->getFailed(),
        ], ApiResponse::STATUS_INVALID, [
            'One or more fields are invalid. Please check your input.',
        ]);
    }
}
