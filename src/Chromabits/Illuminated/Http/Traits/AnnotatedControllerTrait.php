<?php

namespace Chromabits\Illuminated\Http\Traits;

use Chromabits\Nucleus\Support\Arr;

/**
 * Trait AnnotatedControllerTrait.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Http\Traits
 */
trait AnnotatedControllerTrait
{
    /**
     * Get a text description for a method.
     *
     * @param $methodName
     *
     * @return string
     */
    public function getMethodDescription($methodName)
    {
        if ($this->methodDescriptions
            && Arr::has($this->methodDescriptions, $methodName)) {
            return $this->methodDescriptions[$methodName];
        }

        return 'This method does not provide a description';
    }

    /**
     * Get the display name of a method.
     *
     * @param string $methodName
     *
     * @return string
     */
    public function getMethodName($methodName)
    {
        if ($this->methodNames && Arr::has($this->methodNames, $methodName)) {
            return $this->methodNames[$methodName];
        }

        return 'Unknown';
    }

    /**
     * Get the required headers for a method.
     *
     * @return array
     */
    public function getMethodHeaders()
    {
        return [];
    }
}