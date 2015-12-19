<?php

namespace Chromabits\Illuminated\Http\Interfaces;

/**
 * Interface AnnotatedControllerInterface.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Http\Interfaces
 */
interface AnnotatedControllerInterface
{
    /**
     * Get a text description for a method.
     *
     * @param $methodName
     *
     * @return string
     */
    public function getMethodDescription($methodName);

    /**
     * Get the display name of a method.
     *
     * @param string $methodName
     *
     * @return string
     */
    public function getMethodName($methodName);

    /**
     * Get the required headers for a method.
     *
     * @return array
     */
    public function getMethodHeaders();
}