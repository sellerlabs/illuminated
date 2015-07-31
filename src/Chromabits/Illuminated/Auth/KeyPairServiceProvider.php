<?php

namespace Chromabits\Illuminated\Auth;

use Chromabits\Illuminated\Auth\Interfaces\KeyPairFinderInterface;
use Chromabits\Illuminated\Auth\Interfaces\KeyPairGeneratorInterface;
use Chromabits\Illuminated\Auth\Models\KeyPair;
use Chromabits\Illuminated\Support\ServiceMapProvider;

/**
 * Class KeyPairServiceProvider
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
     * Boot the provider
     */
    public function boot()
    {
        KeyPair::registerEvents();
    }
}
