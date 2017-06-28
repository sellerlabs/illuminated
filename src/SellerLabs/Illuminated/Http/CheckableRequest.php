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
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use SellerLabs\Nucleus\Exceptions\LackOfCoffeeException;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Meditation\Exceptions\FailedCheckException;
use SellerLabs\Nucleus\Meditation\Interfaces\CheckableInterface;
use SellerLabs\Nucleus\Meditation\Interfaces\CheckResultInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class CheckableRequest.
 *
 * Similar to FormRequests in Laravel, CheckableRequest lets you abstract the
 * process of validating input away from your controllers.
 *
 * Whenever your CheckableRequest class is resolved by the container, its
 * internal check will be ran against the incoming request. If the check fails,
 * your class should throw an exception which will prevent the execution flow
 * from reaching your controller. Otherwise, the application continues to the
 * controller, meaning that the input coming in guaranteed to be valid, so your
 * controller can focus on doing business logic.
 *
 * When using this class, make sure that you have proper exception handlers
 * setup which will redirect the user, flash messages, display the appropriate
 * response to the user.
 *
 * @property ParameterBag $headers
 * @property ParameterBag $files
 * @property ParameterBag $query
 * @property ParameterBag $request
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Http
 */
abstract class CheckableRequest extends BaseObject implements
    ValidatesWhenResolved
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var Application
     */
    protected $container;

    /**
     * Construct an instance of a SpecRequest.
     *
     * @param Request $request
     * @param Route $route
     * @param Application $application
     *
     * @throws LackOfCoffeeException
     */
    public function __construct(
        Request $request,
        Route $route,
        Application $application
    ) {
        parent::__construct();

        $this->request = $request;
        $this->route = $route;
        $this->container = $application;
    }

    /**
     * Validate the request using the checkable class.
     *
     * @throws FailedCheckException
     */
    public function validate()
    {
        if ($this->container->bound('illuminated.skipCheckableRequest')) {
            return;
        }

        $check = $this->getCheckable();

        $result = $check->check($this->assemble($this->request));

        if ($result->failed()) {
            $this->handleFailure($check, $result);
        }
    }

    /**
     * Get the check to run (a Spec, a validation, etc).
     *
     * @return CheckableInterface
     */
    abstract public function getCheckable();

    /**
     * Prepare the input for the check.
     *
     * @return array
     */
    protected function assemble()
    {
        return $this->request->all();
    }

    /**
     * Handle the case where check does not pass.
     *
     * Here you can throw an exception, flash messages, etc to the user.
     *
     * @param CheckableInterface $check
     * @param CheckResultInterface $result
     *
     * @throws FailedCheckException
     */
    public function handleFailure(
        CheckableInterface $check,
        CheckResultInterface $result
    ) {
        throw new FailedCheckException($check, $result);
    }

    /**
     * Get the current request.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get a request value.
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->request->get($key, $default);
    }

    /**
     * Provide shortcuts to fields in the inner request.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (in_array($name, ['query', 'request', 'files', 'headers'])) {
            return $this->request->$name;
        }

        parent::__get($name);
    }
}
