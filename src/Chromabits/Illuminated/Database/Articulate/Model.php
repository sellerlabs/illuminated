<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Database\Articulate;

use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * Class Model
 *
 * A simple overlay over Laravel's Eloquent models with some utilities.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Articulate
 */
class Model extends BaseModel
{
    /**
     * Get the table of this model (statically).
     *
     * @return Table
     */
    public static function resolveTable()
    {
        $instance = new static();

        return new Table($instance->getTable());
    }
}
