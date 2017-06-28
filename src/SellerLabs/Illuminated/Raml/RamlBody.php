<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Raml;

use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Foundation\Interfaces\ArrayableInterface;
use SellerLabs\Nucleus\Meditation\Arguments;
use SellerLabs\Nucleus\Meditation\Boa;
use SellerLabs\Nucleus\Support\Std;

/**
 * Class RamlBody.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Raml
 */
class RamlBody extends BaseObject implements ArrayableInterface
{
    /**
     * @var mixed
     */
    protected $example;

    /**
     * @var string
     */
    protected $schema;

    /**
     * @var RamlParameter[]
     */
    protected $formParameters = [];

    /**
     * @return mixed
     */
    public function getExample()
    {
        return $this->example;
    }

    /**
     * @param mixed $example
     *
     * @return RamlBody
     */
    public function setExample($example)
    {
        $new = clone $this;

        $new->example = $example;

        return $new;
    }

    /**
     * @return string
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param string $schema
     *
     * @return RamlBody
     */
    public function setSchema($schema)
    {
        $new = clone $this;

        $new->schema = $schema;

        return $new;
    }

    /**
     * @return RamlParameter[]
     */
    public function getFormParameters()
    {
        return $this->formParameters;
    }

    /**
     * @param RamlParameter[] $formParameters
     *
     * @return RamlBody
     */
    public function setFormParameters($formParameters)
    {
        Arguments::define(Boa::arrOf(Boa::instance(RamlParameter::class)))
            ->check($formParameters);

        $new = clone $this;

        $new->formParameters = $formParameters;

        return $new;
    }

    /**
     * Get an array representation of this object.
     *
     * @return array
     */
    public function toArray()
    {
        return RamlUtils::filterEmptyValues(
            [
                'example' => $this->example,
                'schema' => $this->schema,
                'formParameters' => Std::map(
                    function (RamlParameter $parameter) {
                        return $parameter->toArray();
                    },
                    $this->formParameters
                ),
            ]
        );
    }
}
