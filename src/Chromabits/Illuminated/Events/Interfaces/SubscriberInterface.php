<?php

namespace Chromabits\Illuminated\Events\Interfaces;

use Illuminate\Contracts\Events\Dispatcher;

/**
 * Interface SubscriberInterface
 *
 * This interface describes a class capable of listening to events and handling
 * them.
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
