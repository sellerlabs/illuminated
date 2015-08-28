<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Hashing;

use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Support\Std;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Hashing\BcryptHasher;

/**
 * Class AggregatedHasher.
 *
 * A hasher that combines other hashers together. It is intended to be used for
 * applications where there is a pre-existing database with different kinds of
 * password hashes.
 *
 * The aggregator has a main (target) hasher, and a bunch of supported hashers.
 * While checking a hasher, the hasher will attempt to first check against the
 * target hasher, and then fallback to the others.
 *
 * The target hasher will be used for checking if a hash needs a rehash, and
 * while creating new hashes. The Bcrypt hasher conveniently will detect hashes
 * that are not valid Bcrypt hashes. If you replace the target hasher, make sure
 * it does the same.
 *
 * IMPORTANT: This implementation uses just two hashers: MD5 and Bcrypt. If you
 * are considering adding another hasher, carefully review that the output of
 * the hashers will always be different (string size, format, etc). Otherwise,
 * you may run into collisions and other undesirable effects.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Hashing
 */
class AggregatedHasher extends BaseObject implements Hasher
{
    /**
     * The preferred hasher to use.
     *
     * @var Hasher
     */
    protected $targetHasher;

    /**
     * An array of hasher to fallback to. These should be the less-secure
     * options. There is no need to add the target hasher to this list.
     *
     * @var Hasher[]
     */
    protected $supportedHashers;

    /**
     * Construct an instance of an AggregatedHasher.
     */
    public function __construct()
    {
        parent::__construct();

        $this->targetHasher = new BcryptHasher();

        $this->supportedHashers = [
            new Md5Hasher(),
        ];
    }

    /**
     * Hash the given value.
     *
     * @param string $value
     * @param array $options
     *
     * @return string
     */
    public function make($value, array $options = [])
    {
        return $this->targetHasher->make($value, $options);
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param string $value
     * @param string $hashedValue
     * @param array $options
     *
     * @return bool
     */
    public function check($value, $hashedValue, array $options = [])
    {
        // First, we are optimistic and try to use the target hasher.
        if ($this->targetHasher->check($value, $hashedValue, $options)) {
            return true;
        }

        // Otherwise, we attempt to check if it passes any supported hasher.
        $match = false;

        Std::each(
            function (Hasher $hasher) use (
                $value,
                $hashedValue,
                $options,
                &$match
            ) {
                if ($hasher->check($value, $hashedValue, $options)) {
                    $match = true;
                }
            },
            $this->supportedHashers
        );

        return $match;
    }

    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param string $hashedValue
     * @param array $options
     *
     * @return bool
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        // Here, we assume the target hasher is smart enough to detect if a
        // hash does not have the expected format.
        return $this->targetHasher->needsRehash($hashedValue, $options);
    }
}
