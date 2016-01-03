<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Auth;

use Chromabits\Nucleus\Foundation\Enum;

/**
 * Class KeyPairTypes.
 *
 * Some common types of key pairs.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth
 */
class KeyPairTypes extends Enum
{
    const TYPE_HMAC = 'hmac';
    const TYPE_GENERIC = 'generic';
}
