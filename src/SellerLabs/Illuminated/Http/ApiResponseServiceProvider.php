<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Http;

use SellerLabs\Illuminated\Http\Factories\ApiResponseFactory;
use SellerLabs\Illuminated\Http\Interfaces\ApiResponseFactoryInterface;
use SellerLabs\Illuminated\Support\ServiceMapProvider;

/**
 * Class ApiResponseServiceProvider.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Http
 */
class ApiResponseServiceProvider extends ServiceMapProvider
{
    protected $defer = true;

    protected $map = [
        ApiResponseFactoryInterface::class => ApiResponseFactory::class,
    ];
}
