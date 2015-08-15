<?php

namespace Chromabits\Illuminated\Hashing;

use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Support\Std;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Hashing\BcryptHasher;

/**
 * Class AggregatedHasher
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Hashing
 */
class AggregatedHasher extends BaseObject implements Hasher
{
    /**
     * @var Hasher
     */
    protected $targetHasher;

    /**
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
            function ($index, Hasher $hasher) use (
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
