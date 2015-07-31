<?php

namespace Chromabits\Illuminated\Auth;

use Chromabits\Illuminated\Auth\Models\KeyPair;
use Chromabits\Illuminated\Support\ServiceProvider;

/**
 * Class KeyPairServiceProvider
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth
 */
class KeyPairServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Boot the provider
     */
    public function boot()
    {
        KeyPair::registerEvents();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }
}
