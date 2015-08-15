<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Events\Interfaces;

use Illuminate\Contracts\Events\Dispatcher;

/**
 * Interface SubscriberInterface.
 *
 * This interface describes a class capable of listening to events and handling
 * them. Its purpose is to provide an actual definition of a subscriber rather
 * than just following a documentation page.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Events\Interfaces
 */
interface SubscriberInterface
{
    /**
     * Subscribe to events.
     *
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events);
}
