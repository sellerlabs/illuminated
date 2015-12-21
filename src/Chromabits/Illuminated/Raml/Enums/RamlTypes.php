<?php

namespace Chromabits\Illuminated\Raml\Enums;

use Chromabits\Nucleus\Support\Enum;

/**
 * Class RamlTypes.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Raml\Enums
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