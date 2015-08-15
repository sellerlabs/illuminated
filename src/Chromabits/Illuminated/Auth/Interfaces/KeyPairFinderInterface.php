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
 * Interface KeyPairFinderInterface.
 *
 * Finds key pairs
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth\Interfaces
 */
interface KeyPairFinderInterface
{
    /**
     * Find a key pair by its public id.
     *
     * @param string $publicId
     * @param string $type
     *
     * @return KeyPair
     */
    public function byPublicId($publicId, $type);
}
