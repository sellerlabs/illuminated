<?php

namespace Chromabits\Illuminated\Raml;

use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Foundation\Interfaces\ArrayableInterface;
use Chromabits\Nucleus\Support\Std;

/**
 * Class RamlResponseBody.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Raml
 */
class RamlResponseBody extends BaseObject implements ArrayableInterface
{
    /**
     * @var RamlBody[]
     */
    protected $bodyTypes;

    /**
     * Construct an instance of a RamlResponseBody.
     */
    public function __construct()
    {
        parent::__construct();

        $this->bodyTypes = [];
    }

    /**
     * @param $mimeType
     * @param RamlBody $body
     *
     * @return RamlResponseBody
     */
    public function addType($mimeType, RamlBody $body)
    {
        $this->bodyTypes[$mimeType] = $body;

        return $this;
    }

    /**
     * @return RamlBody[]
     */
    public function getBodyTypes()
    {
        return $this->bodyTypes;
    }

    /**
     * Get an array representation of this object.
     *
     * @return array
     */
    public function toArray()
    {
        return Std::map(function (RamlBody $body) {
            return $body->toArray();
        }, $this->bodyTypes);
    }
}