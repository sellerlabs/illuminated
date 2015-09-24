<?php

namespace Chromabits\Illuminated\Conference\Exceptions;

use Chromabits\Nucleus\Exceptions\CoreException;

/**
 * Class ModuleNotFoundException
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Conference\Exceptions
 */
class ModuleNotFoundException extends CoreException
{
    /**
     * Construct an instance of a ModuleNotFoundException.
     *
     * @param string $moduleName
     * @param int $code
     * @param CoreException|null $previous
     */
    public function __construct(
        $moduleName,
        $code = 0,
        CoreException $previous = null
    ) {
        parent::__construct(
            vsprintf(
                'The module `%s` was not found in this dashboard.',
                [$moduleName]
            ),
            $code,
            $previous
        );
    }
}