<?php

namespace Chromabits\Illuminated\Raml;

use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Foundation\Interfaces\ArrayableInterface;

/**
 * Class RamlResponse.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Raml
 */
class RamlResponse extends BaseObject implements ArrayableInterface
{
    /**
     * @var string
     */
    protected $description;

    /**
     * @var RamlResponseBody
     */
    protected $body;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return RamlResponse
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return RamlResponseBody
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param RamlResponseBody $body
     *
     * @return RamlResponse
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get an array representation of this object.
     *
     * @return array
     */
    public function toArray()
    {
        return RamlUtils::filterEmptyValues([
            'description' => $this->description,
            'body' => $this->body ? $this->body->toArray() : null,
        ]);
    }
}