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
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Route;
use SellerLabs\Illuminated\Alerts\Alert;
use SellerLabs\Illuminated\Contracts\Alerts\AlertManager;
use SellerLabs\Nucleus\Meditation\Interfaces\CheckableInterface;
use SellerLabs\Nucleus\Meditation\Interfaces\CheckResultInterface;
use SellerLabs\Nucleus\Support\Std;
use SellerLabs\Nucleus\Validation\Validator;

/**
 * Class FrontCheckableRequest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Http
 */
abstract class FrontCheckableRequest extends CheckableRequest
{
    /**
     * @var AlertManager
     */
    protected $alerts;

    /**
     * @var Redirector
     */
    protected $redirector;

    /**
     * @var string[]
     */
    protected $dontFlash = [];

    /**
     * @var string
     */
    protected $redirect;

    /**
     * @var string
     */
    protected $redirectRoute;

    /**
     * @var string
     */
    protected $redirectAction;

    /**
     * Construct an instance of a FrontCheckableRequest.
     *
     * @param Request $request
     * @param Route $route
     * @param Application $application
     * @param AlertManager $alerts
     * @param Redirector $redirector
     */
    public function __construct(
        Request $request,
        Route $route,
        Application $application,
        AlertManager $alerts,
        Redirector $redirector
    ) {
        parent::__construct($request, $route, $application);

        $this->alerts = $alerts;
        $this->redirector = $redirector;
    }

    /**
     * Report errors to the alerts manager and redirect to a page.
     *
     * @param CheckableInterface $check
     * @param CheckResultInterface $result
     */
    public function handleFailure(
        CheckableInterface $check,
        CheckResultInterface $result
    ) {
        $response = $this->redirector
            ->to($this->getRedirectUrl())
            ->withInput($this->request->except($this->dontFlash));

        if (count($result->getFailed())) {
            if ($check instanceof Validator) {
                $messages = Std::map(function ($value) {
                    return implode(', ', $value);
                }, $result->getFailed());

                $this->pushValidationAlert($messages);
            } else {
                $this->pushValidationSpecAlert($result);
            }
        }

        if (count($result->getMissing())) {
            $this->pushMissingAlert($result->getMissing());
        }

        throw new HttpResponseException($response);
    }

    /**
     * Push a simple validation alert with an array of messages.
     *
     * @param string[] $messages
     */
    protected function pushValidationAlert($messages)
    {
        $this->alerts->push(
            $messages,
            Alert::TYPE_VALIDATION,
            'Oops...',
            'alerts.validation'
        );
    }

    /**
     * Push an alert describing the result of a spec.
     *
     * @param CheckResultInterface $result
     */
    protected function pushValidationSpecAlert(CheckResultInterface $result)
    {
        $this->alerts->push(
            $result->getFailed(),
            Alert::TYPE_VALIDATION,
            'Oops...',
            'alerts.spec'
        );
    }

    /**
     * Push an alert informing the user about missing fields.
     *
     * @param string[] $missingFields
     */
    protected function pushMissingAlert($missingFields)
    {
        $this->alerts->push(
            $missingFields,
            Alert::TYPE_VALIDATION,
            'The following fields are missing:',
            'alerts.validation'
        );
    }

    /**
     * Get the URL to redirect to on a validation error.
     *
     * Copied from Laravel's FormRedirect.
     *
     * @return string
     */
    protected function getRedirectUrl()
    {
        $url = $this->redirector->getUrlGenerator();

        if ($this->redirect) {
            return $url->to($this->redirect);
        } elseif ($this->redirectRoute) {
            return $url->route($this->redirectRoute);
        } elseif ($this->redirectAction) {
            return $url->action($this->redirectAction);
        }

        return $url->previous();
    }
}
