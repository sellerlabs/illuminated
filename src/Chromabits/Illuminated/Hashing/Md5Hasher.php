<?php

namespace Chromabits\Illuminated\Hashing;

use Chromabits\Nucleus\Foundation\BaseObject;
use Illuminate\Contracts\Hashing\Hasher;

/**
 * Class Md5Hasher
 *
 * We don't live in a perfect world. Sometimes you are not starting a new
 * project. Sometimes you have to rewrite an existing application that made a
 * poor choice when it came to its password hashing.
 *
 * This class implements a Laravel hasher that uses MD5. This should be used in
 * coordination with other hasher, not by itself. If your application finds a
 * password stored as an MD5 hash, it should rehash it in a more secure format.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Hashing
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
