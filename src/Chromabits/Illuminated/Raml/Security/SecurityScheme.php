<?php

namespace Chromabits\Illuminated\Raml\Security;

use Chromabits\Illuminated\Raml\RamlParameter;
use Chromabits\Illuminated\Raml\RamlResponseGroup;
use Chromabits\Illuminated\Raml\RamlUtils;
use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Foundation\Interfaces\ArrayableInterface;
use Chromabits\Nucleus\Support\Std;

/**
 * Class SecurityScheme.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Raml\Security
 */
abstract class SecurityScheme extends BaseObject implements ArrayableInterface
{
    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var RamlParameter[]
     */
    protected $headers = [];

    /**
     * @var RamlParameter
     */
    protected $queryParameters = [];

    /**
     * @var RamlResponseGroup
     */
    protected $responses = [];

    /**
     * @var array
     */
    protected $settings;

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
     * @return SecurityScheme
     */
    public function setDescription($description)
    {
        $new = clone $this;

        $new->description = $description;

        return $new;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return SecurityScheme
     */
    public function setType($type)
    {
        $new = clone $this;

        $new->type = $type;

        return $new;
    }

    /**
     * @return RamlParameter[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param RamlParameter[] $headers
     *
     * @return SecurityScheme
     */
    public function setHeaders($headers)
    {
        $new = clone $this;

        $new->headers = $headers;

        return $new;
    }

    /**
     * @return RamlParameter
     */
    public function getQueryParameters()
    {
        return $this->queryParameters;
    }

    /**
     * @param RamlParameter $queryParameters
     *
     * @return SecurityScheme
     */
    public function setQueryParameters($queryParameters)
    {
        $new = clone $this;

        $new->queryParameters = $queryParameters;

        return $new;
    }

    /**
     * @return RamlResponseGroup
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * @param RamlResponseGroup $responses
     *
     * @return SecurityScheme
     */
    public function setResponses($responses)
    {
        $new = clone $this;

        $new->responses = $responses;

        return $new;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     *
     * @return SecurityScheme
     */
    public function setSettings($settings)
    {
        $new = clone $this;

        $new->settings = $settings;

        return $new;
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
            'type' => $this->type,
            'describedBy' => RamlUtils::filterEmptyValues([
                'headers' => Std::map(function (RamlParameter $header) {
                    return $header->toArray();
                }, $this->headers),
                'queryParameters' => Std::map(function (RamlParameter $query) {
                    return $query->toArray();
                }, $this->queryParameters),
                'responses' => $this->responses ?
                    $this->responses->toArray() : null,
            ]),
            'settings' => $this->settings,
        ]);
    }
}