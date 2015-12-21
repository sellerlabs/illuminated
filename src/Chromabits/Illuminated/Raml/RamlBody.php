<?php

namespace Chromabits\Illuminated\Raml;

use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Foundation\Interfaces\ArrayableInterface;
use Chromabits\Nucleus\Meditation\Arguments;
use Chromabits\Nucleus\Meditation\Boa;
use Chromabits\Nucleus\Support\Std;

/**
 * Class RamlBody.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Raml
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
        $this->example = $example;

        return $this;
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
        $this->schema = $schema;

        return $this;
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

        $this->formParameters = $formParameters;

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
            'example' => $this->example,
            'schema' => $this->schema,
            'formParameters' => Std::map(function (RamlParameter $parameter) {
                return $parameter->toArray();
            }, $this->formParameters),
        ]);
    }
}