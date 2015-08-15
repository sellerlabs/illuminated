<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Auth\Interfaces;

use Chromabits\Illuminated\Auth\Models\KeyPair;

/**
 * Interface KeyPairGeneratorInterface.
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
}
