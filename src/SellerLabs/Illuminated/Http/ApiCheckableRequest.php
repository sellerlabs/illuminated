<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Http;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use SellerLabs\Illuminated\Http\Interfaces\ApiResponseFactoryInterface;
use SellerLabs\Nucleus\Meditation\Interfaces\CheckableInterface;
use SellerLabs\Nucleus\Meditation\Interfaces\CheckResultInterface;

/**
 * Class ApiCheckableRequest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Http
 */
abstract class ApiCheckableRequest extends CheckableRequest
{
    /**
     * @var ApiResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * Construct an instance of a ApiCheckableRequest.
     *
     * @param Request $request
     * @param Route $route
     * @param Application $application
     * @param ApiResponseFactoryInterface $responseFactory
     */
    public function __construct(
        Request $request,
        Route $route,
        Application $application,
        ApiResponseFactoryInterface $responseFactory
    ) {
        parent::__construct($request, $route, $application);

        $this->responseFactory = $responseFactory;
    }

    /**
     * Handle the case where check does not pass.
     *
     * Here you can throw an exception, flash messages, etc to the user.
     *
     * @param CheckableInterface $check
     * @param CheckResultInterface $result
     */
    public function handleFailure(
        CheckableInterface $check,
        CheckResultInterface $result
    ) {
        throw new HttpResponseException(
            $this->responseFactory->fromCheckable($result)->toResponse()
        );
    }
}
