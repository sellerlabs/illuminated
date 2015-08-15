<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Auth;

use Chromabits\Illuminated\Auth\Interfaces\KeyPairFinderInterface;
use Chromabits\Illuminated\Auth\Interfaces\KeyPairGeneratorInterface;
use Chromabits\Illuminated\Auth\Models\KeyPair;
use Chromabits\Illuminated\Support\ServiceMapProvider;

/**
 * Class KeyPairServiceProvider.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth
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
