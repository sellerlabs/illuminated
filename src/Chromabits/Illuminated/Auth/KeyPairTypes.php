<?php

namespace Chromabits\Illuminated\Auth;

use Chromabits\Nucleus\Support\Enum;

/**
 * Class KeyPairTypes
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
