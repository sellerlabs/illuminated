<?php

namespace Chromabits\Illuminated\Auth\Interfaces;

use Chromabits\Illuminated\Auth\Models\KeyPair;
use Chromabits\Nucleus\Exceptions\CoreException;

/**
 * Interface KeyPairGeneratorInterface
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth\Interfaces
 */
interface KeyPairGeneratorInterface
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
    );

    /**
     * Generate a key pair.
     *
     * @param string $type
     * @param array $attributes
     *
     * @return KeyPair
     * @throws CoreException
     */
    public function generate($type, $attributes = []);
}
