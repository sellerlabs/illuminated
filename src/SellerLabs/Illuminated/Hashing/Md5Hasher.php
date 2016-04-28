<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Hashing;

use Illuminate\Contracts\Hashing\Hasher;
use SellerLabs\Nucleus\Foundation\BaseObject;

/**
 * Class Md5Hasher.
 *
 * We don't live in a perfect world. Sometimes you are not starting a new
 * project. Sometimes you have to rewrite an existing application that made a
 * poor choice when it came to its password hashing.
 *
 * This class implements a Laravel hasher that uses MD5. This should be used in
 * coordination with other hasher, not by itself. If your application finds a
 * password stored as an MD5 hash, it should rehash it in a more secure format.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Hashing
 */
class Md5Hasher extends BaseObject implements Hasher
{
    /**
     * Hash the given value.
     *
     * @param  string $value
     * @param  array $options
     *
     * @return string
     */
    public function make($value, array $options = [])
    {
        return md5($value);
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param  string $value
     * @param  string $hashedValue
     * @param  array $options
     *
     * @return bool
     */
    public function check($value, $hashedValue, array $options = [])
    {
        return $hashedValue === md5($value);
    }

    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param  string $hashedValue
     * @param  array $options
     *
     * @return bool
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }
}
