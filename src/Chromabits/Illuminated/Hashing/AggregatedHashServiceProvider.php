<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Hashing;

use Chromabits\Illuminated\Support\ServiceProvider;
use Illuminate\Contracts\Hashing\Hasher;

/**
 * Class AggregatedHashServiceProvider.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Hashing
 */
class AggregatedHashServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton(['hash', Hasher::class], function () {
            return new AggregatedHasher();
        });
    }

    /**
     * Return list of services provided.
     *
     * @return array
     */
    public function provides()
    {
        return ['hash', Hasher::class];
    }
}
