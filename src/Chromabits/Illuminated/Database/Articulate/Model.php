<?php

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
