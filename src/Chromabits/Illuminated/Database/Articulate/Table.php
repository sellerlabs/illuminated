<?php

/**
 * Copyright 2015, Eduardo Trujillo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Laravel Helpers package
 */

namespace Chromabits\Illuminated\Database\Articulate;

use Chromabits\Nucleus\Exceptions\LackOfCoffeeException;

/**
 * Class Table
 *
 * A barebones abstraction of a database table.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Articulate
 */
class Table
{
    const TYPE_CHAR = 'CHAR';
    const TYPE_BINARY = 'BINARY';
    const TYPE_VARCHAR = 'VARCHAR';
    const TYPE_TINYTEXT = 'TINYTEXT';
    const TYPE_TINYBLOB = 'TINYBLOB';
    const TYPE_TEXT = 'TEXT';
    const TYPE_BLOB = 'BLOB';
    const TYPE_MEDIUMTEXT = 'MEDIUMTEXT';
    const TYPE_MEDIUMBLOB = 'MEDIUMBLOB';
    const TYPE_LONGTEXT = 'LONGTEXT';
    const TYPE_LONGBLOB = 'LONGBLOB';

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $name;

    /**
     * Construct an instance of a Table.
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the table.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the full name of a table field (for joins, etc).
     *
     * @param $name
     *
     * @return string
     */
    public function field($name)
    {
        return $this->name . '.' . $name;
    }

    /**
     * Determine if some value fits inside a database column.
     *
     * Right now this check is limited to string values. Future versions might
     * support binary data and numbers as well.
     *
     * @param $content
     * @param $type
     * @param null $length
     *
     * @return bool
     * @throws \Chromabits\Nucleus\Exceptions\LackOfCoffeeException
     */
    public static function fits($content, $type, $length = null)
    {
        switch ($type) {
            case static::TYPE_CHAR:
            case static::TYPE_VARCHAR:
                return within(0, coalesce($length, 255), strlen($content));
            case static::TYPE_TINYTEXT:
                return within(0, coalesce($length, 2 ** 8), strlen($content));
            case static::TYPE_TEXT:
                return within(0, coalesce($length, 2 ** 16), strlen($content));
            case static::TYPE_MEDIUMTEXT:
                return within(0, coalesce($length, 2 ** 24), strlen($content));
            case static::TYPE_LONGTEXT:
                return within(0, coalesce($length, 2 ** 32), strlen($content));
        }

        throw new LackOfCoffeeException('Not implemented.');
    }
}
