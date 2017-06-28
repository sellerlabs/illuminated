<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Raml;

use SellerLabs\Nucleus\Foundation\StaticObject;
use SellerLabs\Nucleus\Support\Arr;

/**
 * Class RamlUtils.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Raml
 */
class RamlUtils extends StaticObject
{
    /**
     * Only return non-null and non-empty values in an array.
     *
     * @param array $properties
     * @param array|null $allowed
     *
     * @return array
     */
    public static function filterEmptyValues($properties, array $allowed = null)
    {
        // If provided, only use allowed properties
        $properties = Arr::only($properties, $allowed);

        return array_filter(
            $properties,
            function ($value) {
                if (is_array($value) && count($value) === 0) {
                    return false;
                }

                return !is_null($value);
            }
        );
    }
}
