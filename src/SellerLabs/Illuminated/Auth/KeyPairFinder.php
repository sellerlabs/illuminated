<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Auth;

use SellerLabs\Illuminated\Auth\Interfaces\KeyPairFinderInterface;
use SellerLabs\Illuminated\Auth\Models\KeyPair;
use SellerLabs\Nucleus\Foundation\BaseObject;

/**
 * Class KeyPairFinder.
 *
 * Finds key pairs
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Auth
 */
class KeyPairFinder extends BaseObject implements KeyPairFinderInterface
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
