<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Database\Articulate;

/**
 * Class ValidatingTrait.
 *
 * Originally from: https://github.com/AltThree/Validator/
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Database\Articulate
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
