<?php

namespace Chromabits\Illuminated\Auth;

use Chromabits\Illuminated\Auth\Models\KeyPair;

/**
 * Class KeyPairFinder
 *
 * Finds key pairs
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth
 */
class KeyPairFinder
{
    /**
     * Find a key pair by its public id.
     *
     * @param string $publicId
     * @param string $type
     *
     * @return KeyPair
     */
    public function byPublicId($publicId, $type)
    {
        return KeyPair::query()
            ->where('public_id', $publicId)
            ->where('type', $type)
            ->firstOrFail();
    }
}
