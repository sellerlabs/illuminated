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
 * Class MethodNotFoundException.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Exceptions
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
