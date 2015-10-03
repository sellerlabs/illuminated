<?php
/**
 * Created by PhpStorm.
 * User: etcinit
 * Date: 10/3/15
 * Time: 6:57 PM
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
     * @return ApiResponse
     * @throws LackOfCoffeeException
     */
    public function fromCheckable(CheckResultInterface $result)
    {
        if ($result->passed()) {
            throw new LackOfCoffeeException(
                'You are trying to send a validation error response,'
                . ' but your check actually passed!'
            );
        }

        return new static([
            'missing' => $result->getMissing(),
            'validation' => $result->getFailed(),
        ], ApiResponse::STATUS_INVALID, [
            'One or more fields are invalid. Please check your input.',
        ]);
    }
}