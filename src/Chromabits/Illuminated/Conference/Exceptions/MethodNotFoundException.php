<?php

namespace Chromabits\Illuminated\Conference\Exceptions;

use Chromabits\Nucleus\Exceptions\CoreException;

/**
 * Class MethodNotFoundException
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Conference\Exceptions
 */
class MethodNotFoundException extends CoreException
{
    /**
     * Construct an instance of a MethodNotFoundException.
     *
     * @param string $moduleName
     * @param int $methodName
     * @param int $code
     * @param CoreException|null $previous
     */
    public function __construct(
        $moduleName,
        $methodName,
        $code = 0,
        CoreException $previous = null
    ) {
        parent::__construct(
            vsprintf(
                'The method `%s` was not found in the `%s` module.',
                [$methodName, $moduleName]
            ),
            $code,
            $previous
        );
    }
}