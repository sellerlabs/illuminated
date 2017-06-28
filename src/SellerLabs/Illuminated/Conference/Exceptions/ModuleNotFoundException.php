<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Conference\Exceptions;

use SellerLabs\Nucleus\Exceptions\CoreException;

/**
 * Class ModuleNotFoundException.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Exceptions
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
