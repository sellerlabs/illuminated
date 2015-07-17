<?php

/**
 * Copyright 2015, Eduardo Trujillo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Laravel Helpers package
 */

namespace Tests\Chromabits\Illuminated\Queue;

use Chromabits\Illuminated\Queue\Interfaces\QueuePusherInterface;
use Chromabits\Illuminated\Queue\QueueServiceProvider;
use Chromabits\Illuminated\Testing\ServiceProviderTestCase;
use Illuminate\Foundation\Application;

/**
 * Class QueueServiceProviderTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Queue
 */
class QueueServiceProviderTest extends ServiceProviderTestCase
{
    protected $shouldBeBound = [
        QueuePusherInterface::class,
    ];

    /**
     * Make an instance of the service provider being tested
     *
     * @param Application $app
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function make(Application $app)
    {
        return new QueueServiceProvider($app);
    }
}
