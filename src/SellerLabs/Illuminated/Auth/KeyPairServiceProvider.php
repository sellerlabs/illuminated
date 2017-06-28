<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Auth;

use SellerLabs\Illuminated\Auth\Interfaces\KeyPairFinderInterface;
use SellerLabs\Illuminated\Auth\Interfaces\KeyPairGeneratorInterface;
use SellerLabs\Illuminated\Auth\Models\KeyPair;
use SellerLabs\Illuminated\Support\ServiceMapProvider;

/**
 * Class KeyPairServiceProvider.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Auth
 */
class KeyPairServiceProvider extends ServiceMapProvider
{
    protected $defer = false;

    protected $map = [
        KeyPairFinderInterface::class => KeyPairFinder::class,
        KeyPairGeneratorInterface::class => KeyPairGenerator::class,
    ];

    /**
     * Boot the provider.
     */
    public function boot()
    {
        KeyPair::registerEvents();
    }
}
