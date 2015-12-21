<?php

namespace Chromabits\Illuminated\Raml;

use Chromabits\Illuminated\Raml\Enums\RamlTypes;
use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Foundation\Interfaces\ArrayableInterface;
use Chromabits\Nucleus\Meditation\Arguments;
use Chromabits\Nucleus\Meditation\Boa;

/**
 * Class RamlParameters.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Raml
 */
class RamlParameter extends BaseObject implements ArrayableInterface
{
    /**
     * @var string
     */
    protected $displayName;

    /**
     *
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $enum;

    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var int
     */
    protected $minLength;

    /**
     * @var int
     */
    protected $maxLength;

    /**
     * @var int|float
     */
    protected $minimum;

    /**
     * @var int|float
     */
    protected $maximum;

    /**
     * @var mixed
     */
    protected $example;

    /**
     * @var bool
     */
    protected $repeat;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var mixed
     */
    protected $default;

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     *
     * @return RamlParameter
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

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
     * @return RamlParameter
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
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
     * @return RamlParameter
     */
    public function setType($type)
    {
        Arguments::define(Boa::in(RamlTypes::getValues()))->check($type);

        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function getEnum()
    {
        return $this->enum;
    }

    /**
     * @param array $enum
     *
     * @return RamlParameter
     */
    public function setEnum($enum)
    {
        $this->enum = $enum;

        return $this;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     *
     * @return RamlParameter
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinLength()
    {
        return $this->minLength;
    }

    /**
     * @param int $minLength
     *
     * @return RamlParameter
     */
    public function setMinLength($minLength)
    {
        $this->minLength = $minLength;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxLength()
    {
        return $this->maxLength;
    }

    /**
     * @param int $maxLength
     *
     * @return RamlParameter
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * @param float|int $minimum
     *
     * @return RamlParameter
     */
    public function setMinimum($minimum)
    {
        $this->minimum = $minimum;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     * @param float|int $maximum
     *
     * @return RamlParameter
     */
    public function setMaximum($maximum)
    {
        $this->maximum = $maximum;

        return $this;
    }

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
     * @return RamlParameter
     */
    public function setExample($example)
    {
        $this->example = $example;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isRepeat()
    {
        return $this->repeat;
    }

    /**
     * @param boolean $repeat
     *
     * @return RamlParameter
     */
    public function setRepeat($repeat)
    {
        $this->repeat = $repeat;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param boolean $required
     *
     * @return RamlParameter
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     *
     * @return RamlParameter
     */
    public function setDefault($default)
    {
        $this->default = $default;

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
            'displayName' => $this->displayName,
            'description' => $this->description,
            'type' => $this->type,
            'enum' => $this->enum,
            'pattern' => $this->pattern,
            'minLength' => $this->minLength,
            'maxLength' => $this->maxLength,
            'minimum' => $this->minimum,
            'maximum' => $this->maximum,
            'example' => $this->example,
            'repeat' => $this->repeat,
            'required' => $this->required,
            'default' => $this->default,
        ]);
    }
}