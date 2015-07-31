<?php

namespace Chromabits\Illuminated\Auth;

use Chromabits\Illuminated\Auth\Interfaces\KeyPairGeneratorInterface;
use Chromabits\Illuminated\Auth\Models\KeyPair;
use Chromabits\Nucleus\Exceptions\CoreException;
use Chromabits\Nucleus\Support\Str;

/**
 * Class KeyPairGenerator
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth
 */
class KeyPairGenerator
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
