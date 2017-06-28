<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Http\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface AnnotatedControllerInterface.
 *
 * Describes a controller class that is capable of providing additional
 * context on its method, which can be used to generate documentation and
 * clients.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Http\Interfaces
 */
interface AnnotatedControllerInterface
{
    /**
     * Get a text description for a method.
     *
     * @param string $methodName
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
     * @param string $methodName
     *
     * @return array
     */
    public function getMethodHeaders($methodName);

    /**
     * Get a list of example requests for a specific method.
     *
     * @param string $methodName
     *
     * @return Request[]
     */
    public function getMethodExampleRequests($methodName);

    /**
     * Get a list of example responses for a specific method.
     *
     * @param string $methodName
     *
     * @return Response[]
     */
    public function getMethodExampleResponses($methodName);
}
