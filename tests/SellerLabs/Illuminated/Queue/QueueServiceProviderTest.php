<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Queue;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use SellerLabs\Illuminated\Queue\Interfaces\QueuePusherInterface;
use SellerLabs\Illuminated\Queue\QueueServiceProvider;
use SellerLabs\Illuminated\Testing\ServiceProviderTestCase;

/**
 * Class QueueServiceProviderTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Queue
 */
class QueueServiceProviderTest extends ServiceProviderTestCase
{
    protected $shouldBeBound = [
        QueuePusherInterface::class,
    ];

    /**
     * Make an instance of the service provider being tested.
     *
     * @param Application $app
     *
     * @return ServiceProvider
     */
    public function make(Application $app)
    {
        return new QueueServiceProvider($app);
    }
}
