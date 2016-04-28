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

use SellerLabs\Nucleus\Foundation\Enum;

/**
 * Class KeyPairTypes.
 *
 * Some common types of key pairs.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Auth
 */
class KeyPairTypes extends Enum
{
    const TYPE_HMAC = 'hmac';
    const TYPE_GENERIC = 'generic';
}
