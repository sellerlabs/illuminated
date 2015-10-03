<?php

namespace Chromabits\Illuminated\Http;

use Chromabits\Illuminated\Http\Factories\ApiResponseFactory;
use Chromabits\Illuminated\Http\Interfaces\ApiResponseFactoryInterface;
use Chromabits\Illuminated\Support\ServiceMapProvider;

/**
 * Class ApiResponseServiceProvider
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Http
 */
class ApiResponseServiceProvider extends ServiceMapProvider
{
    protected $map = [
        ApiResponseFactoryInterface::class => ApiResponseFactory::class,
    ];
}