<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Database\Articulate;

/**
 * Class ValidatingTrait.
 *
 * Originally from: https://github.com/AltThree/Validator/
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Articulate
 */
trait ValidatingTrait
{
    /**
     * Setup the validating observer.
     *
     */
    public static function bootValidatingTrait()
    {
        static::observe(new ValidatingObserver());
    }
}
