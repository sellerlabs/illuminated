<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Contracts\Alerts;

use Chromabits\Illuminated\Alerts\Alert;
use Illuminate\Contracts\Validation\Validator;

/**
 * Interface AlertManager
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Contracts\Alerts
 */
interface AlertManager
{
    /**
     * Creates and adds a new alert into the store
     *
     * This is factory function that will create a new Alert object and
     * add it to the session collection so that it may be accessed later
     *
     * @param $content
     * @param string $type
     * @param string|null $title
     * @param string|null $view
     */
    public function push(
        $content,
        $type = Alert::TYPE_NEUTRAL,
        $title = null,
        $view = null
    );

    /**
     * Creates an alert from the result of validator
     *
     * The type of the alert will be TYPE_VALIDATION
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @param string|null $title
     * @param string|null $view
     */
    public function pushValidation(Validator $validator, $title, $view);

    /**
     * Take a look at the oldest alert in the collection
     *
     * @return Alert|null
     */
    public function peek();

    /**
     * Get all the alerts currently in the store
     *
     * @return Alert[]
     */
    public function peekAll();

    /**
     * Take the oldest alert out of the collection and render it
     *
     * @return null|string
     */
    public function takeAndRender();

    /**
     * Take the oldest alert out of the collection
     *
     * @return Alert|null
     */
    public function take();

    /**
     * Clear the collection and return all the alerts
     *
     * @return array
     */
    public function all();

    /**
     * Render all the alerts and then clear the collection
     *
     * @return array
     */
    public function allAndRender();
}
