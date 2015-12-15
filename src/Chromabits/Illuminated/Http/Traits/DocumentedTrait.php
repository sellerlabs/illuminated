<?php

namespace Chromabits\Illuminated\Http\Traits;

use Chromabits\Illuminated\Http\Entities\ResourceMethod;

/**
 * Trait DocumentedTrait.
 *
 * @property string $description
 * @property string $prose
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Http\Traits
 */
trait DocumentedTrait
{
    /**
     * Get a short description of the controller.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get prose (long-form documentation) on the controller.
     *
     * @return string
     */
    public function getProse()
    {
        return $this->prose;
    }

    /**
     * Get prose (long-form documentation) on a method.
     *
     * @param ResourceMethod $method
     *
     * @return string|null
     */
    public function getMethodProse(ResourceMethod $method)
    {
        $proseMethod = $method->getMethod() . 'Prose';

        if (method_exists($this, $proseMethod)) {
            return $proseMethod();
        }

        return null;
    }
}