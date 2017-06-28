<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Raml;

use SellerLabs\Illuminated\Raml\Interfaces\RamlEncoderInterface;
use SellerLabs\Illuminated\Support\ServiceMapProvider;

/**
 * Class RamlServiceProvider.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Raml
 */
class RamlServiceProvider extends ServiceMapProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @var array
     */
    protected $map = [
        RamlEncoderInterface::class => RamlEncoder::class,
    ];
}
