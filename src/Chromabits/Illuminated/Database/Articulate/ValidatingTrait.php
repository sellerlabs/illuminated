<?php

namespace Chromabits\Illuminated\Database\Articulate;

/**
 * Class ValidatingTrait
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
     * @return void
     */
    public static function bootValidatingTrait()
    {
        static::observe(new ValidatingObserver());
    }
}