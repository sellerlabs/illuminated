<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Conference;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Route;
use SellerLabs\Illuminated\Conference\Entities\ConferenceContext;
use SellerLabs\Illuminated\Contracts\Alerts\AlertManager;
use SellerLabs\Illuminated\Http\FrontCheckableRequest;

/**
 * Class ConferenceFrontCheckableRequest.

 *
*@author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference
 */
abstract class ConferenceFrontCheckableRequest extends FrontCheckableRequest
{
    /**
     * @var ConferenceContext
     */
    protected $context;

    /**
     * Construct an instance of a ConferenceFrontCheckableRequest.
     *
     * @param Request $request
     * @param Route $route
     * @param Application $application
     * @param AlertManager $alerts
     * @param Redirector $redirector
     * @param ConferenceContext $context
     */
    public function __construct(Request $request,
        Route $route,
        Application $application,
        AlertManager $alerts,
        Redirector $redirector,
        ConferenceContext $context
    ) {
        parent::__construct(
            $request,
            $route,
            $application,
            $alerts,
            $redirector
        );
        $this->context = $context;
    }

    /**
     * @return string
     */
    protected function getRedirectUrl()
    {
        return $this->context->lastUrl();
    }
}
