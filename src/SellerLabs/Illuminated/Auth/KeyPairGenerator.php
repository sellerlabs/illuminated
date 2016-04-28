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

use SellerLabs\Illuminated\Auth\Interfaces\KeyPairGeneratorInterface;
use SellerLabs\Illuminated\Auth\Models\KeyPair;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Support\Str;

/**
 * Class KeyPairGenerator.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Auth
 */
class KeyPairGenerator extends BaseObject implements KeyPairGeneratorInterface
{
    /**
     * Generate an HMAC key pair.
     *
     * @param int $generatePublicLength
     * @param int $generateSecretLength
     * @param string $algorithm
     *
     * @return KeyPair
     */
    public function generateHmac(
        $generatePublicLength = 256,
        $generateSecretLength = 512,
        $algorithm = 'sha512'
    ) {
        $pair = new KeyPair();

        $pair->public_id = hash($algorithm, Str::random($generatePublicLength));
        $pair->secret_key = hash(
            $algorithm,
            Str::random($generateSecretLength)
        );
        $pair->type = KeyPairTypes::TYPE_HMAC;
        $pair->data = [];

        return $pair;
    }
}
