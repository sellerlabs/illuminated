<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Queue;

use Chromabits\Illuminated\Queue\Interfaces\QueuePusherInterface;
use Chromabits\Illuminated\Support\ServiceMapProvider;

/**
 * Class QueueServiceProvider.
 *
 * Provides queue utility services.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Queue
 */
class QueueServiceProvider extends ServiceMapProvider
{
    protected $defer = true;

    protected $map = [
        QueuePusherInterface::class => QueuePusher::class,
    ];
}
