<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Raml\Enums;

use SellerLabs\Nucleus\Foundation\Enum;

/**
 * Class RamlTypes.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Raml\Enums
 */
class RamlTypes extends Enum
{
    const TYPE_STRING = 'string';

    const TYPE_NUMBER = 'number';

    const TYPE_INTEGER = 'integer';

    const TYPE_DATE = 'date';

    const TYPE_BOOLEAN = 'boolean';

    const TYPE_FILE = 'file';
}
