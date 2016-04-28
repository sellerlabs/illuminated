<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Auth\Models;

use SellerLabs\Illuminated\Database\Articulate\JsonModel;

/**
 * Class KeyPair.
 *
 * @property int id
 * @property string public_id
 * @property string secret_key
 * @property string type
 * @property array data
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Auth\Models
 */
class KeyPair extends JsonModel
{
    protected $table = 'illuminated_key_pairs';

    /**
     * Fields that should be handled as JSON in the database.
     *
     * @var string[]
     */
    protected $json = ['data'];

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPublicId()
    {
        return $this->public_id;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secret_key;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
