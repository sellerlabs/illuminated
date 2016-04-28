<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Database\Articulate;

use SellerLabs\Nucleus\Exceptions\LackOfCoffeeException;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Support\Std;

/**
 * Class Table.
 *
 * A barebones abstraction of a database table.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Database\Articulate
 */
class Table extends BaseObject
{
    const TYPE_BINARY = 'BINARY';

    const TYPE_BLOB = 'BLOB';

    const TYPE_CHAR = 'CHAR';

    const TYPE_LONGBLOB = 'LONGBLOB';

    const TYPE_LONGTEXT = 'LONGTEXT';

    const TYPE_MEDIUMBLOB = 'MEDIUMBLOB';

    const TYPE_MEDIUMTEXT = 'MEDIUMTEXT';

    const TYPE_TEXT = 'TEXT';

    const TYPE_TINYBLOB = 'TINYBLOB';

    const TYPE_TINYTEXT = 'TINYTEXT';

    const TYPE_VARCHAR = 'VARCHAR';

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $name;

    /**
     * Construct an instance of a Table.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct();

        $this->name = $name;
    }

    /**
     * Determine if some value fits inside a database column.
     *
     * Right now this check is limited to string values. Future versions might
     * support binary data and numbers as well.
     *
     * @param mixed $content
     * @param string $type
     * @param null $length
     *
     * @throws \SellerLabs\Nucleus\Exceptions\LackOfCoffeeException
     * @return bool
     */
    public static function fits($content, $type, $length = null)
    {
        switch ($type) {
            case static::TYPE_CHAR:
            case static::TYPE_VARCHAR:
                return Std::within(
                    0,
                    Std::coalesce($length, 255),
                    strlen($content)
                );
            case static::TYPE_TINYTEXT:
                return Std::within(
                    0,
                    Std::coalesce($length, 2 ** 8),
                    strlen($content)
                );
            case static::TYPE_TEXT:
                return Std::within(
                    0,
                    Std::coalesce($length, 2 ** 16),
                    strlen($content)
                );
            case static::TYPE_MEDIUMTEXT:
                return Std::within(
                    0,
                    Std::coalesce($length, 2 ** 24),
                    strlen($content)
                );
            case static::TYPE_LONGTEXT:
                return Std::within(
                    0,
                    Std::coalesce($length, 2 ** 32),
                    strlen($content)
                );
        }

        throw new LackOfCoffeeException('Not implemented.');
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
     * @param string $name
     *
     * @return string
     */
    public function field($name)
    {
        return $this->name . '.' . $name;
    }
}
