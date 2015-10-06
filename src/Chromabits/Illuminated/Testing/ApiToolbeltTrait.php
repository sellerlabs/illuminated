<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Testing;

use Chromabits\Illuminated\Http\ApiResponse;
use Chromabits\Illuminated\Http\RequestFactory;
use Chromabits\Nucleus\Http\Enums\HttpMethods;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiToolbeltTrait.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Testing
 */
trait ApiToolbeltTrait
{
    protected function parse(Response $response)
    {
        return ApiResponse::fromResponse($response);
    }

    /**
     * @return RequestFactory
     */
    protected function request()
    {
        return RequestFactory::define();
    }

    /**
     * @return RequestFactory
     */
    protected function getRequest()
    {
        return $this->request();
    }

    /**
     * @return RequestFactory
     */
    protected function postRequest()
    {
        return $this->request()->usingMethod(HttpMethods::POST);
    }

    protected function assertSuccessful(ApiResponse $response)
    {
        $this->assertEquals(
            ApiResponse::STATUS_SUCCESS,
            $response->getStatus()
        );
    }
}
