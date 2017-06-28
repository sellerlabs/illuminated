<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Http\Interfaces;

use SellerLabs\Illuminated\Http\ApiResponse;
use SellerLabs\Nucleus\Exceptions\LackOfCoffeeException;
use SellerLabs\Nucleus\Meditation\Interfaces\CheckResultInterface;

/**
 * Interface ApiResponseFactoryInterface.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Http\Interfaces
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
