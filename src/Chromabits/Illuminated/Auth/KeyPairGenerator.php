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
class KeyPairGenerator implements KeyPairGeneratorInterface
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

        return $pair;
    }

    /**
     * Generate a key pair.
     *
     * @param string $type
     * @param array $attributes
     *
     * @return KeyPair
     * @throws CoreException
     */
    public function generate($type, $attributes = [])
    {
        switch ($type) {
            case KeyPairTypes::TYPE_HMAC:
                return $this->generateHmac(
                    $attributes['generatePublicLength'],
                    $attributes['generateSecretLength'],
                    $attributes['algorithm']
                );
        }

        throw new CoreException('Unsupported key-pair type');
    }
}
