<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Http\Entities;

use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Meditation\Arguments;
use SellerLabs\Nucleus\Meditation\Boa;

/**
 * Class ResourceMethod.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Http\Entities
 */
class ResourceMethod extends BaseObject
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $verb;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $where;

    /**
     * Construct an instance of a ResourceMethod.
     *
     * @param string $method
     * @param string $verb
     * @param string $path
     * @param array $where
     */
    public function __construct($method, $verb, $path, $where = [])
    {
        parent::__construct();

        Arguments::define(
            Boa::string(),
            Boa::string(),
            Boa::string()
        )->check($method, $verb, $path);

        $this->method = $method;
        $this->verb = $verb;
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getWhere()
    {
        return $this->where;
    }
}
