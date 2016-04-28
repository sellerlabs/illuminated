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

use Illuminate\Container\Container;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteDependencyResolverTrait;
use SellerLabs\Nucleus\Exceptions\LackOfCoffeeException;
use SellerLabs\Nucleus\Foundation\BaseObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Method.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference
 */
class Method extends BaseObject
{
    use RouteDependencyResolverTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $controllerClassName;

    /**
     * @var string
     */
    protected $controllerMethodName;

    /**
     * @var string
     */
    protected $verb;

    /**
     * @var null|String
     */
    protected $label;

    /**
     * @var bool
     */
    protected $hidden;

    /**
     * @var Container
     */
    protected $container;

    /**
     * Construct an instance of a Method.
     *
     * @param string $name
     * @param string $controllerClassName
     * @param string $controllerMethodName
     * @param string $verb
     *
     * @throws LackOfCoffeeException
     */
    public function __construct(
        $name,
        $controllerClassName,
        $controllerMethodName,
        $verb = 'GET'
    ) {
        parent::__construct();

        $this->name = $name;
        $this->verb = $verb;
        $this->controllerClassName = $controllerClassName;
        $this->controllerMethodName = $controllerMethodName;
        $this->label = null;
        $this->hidden = false;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getControllerClassName()
    {
        return $this->controllerClassName;
    }

    /**
     * @return string
     */
    public function getControllerMethodName()
    {
        return $this->controllerMethodName;
    }

    /**
     * @return string
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Set the instance of a container to use.
     *
     * @param Container $container
     *
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Run the method.
     *
     * @param Request $request
     *
     * @return mixed|Response
     */
    public function run(Request $request)
    {
        $this->container = $this->container ?: new Container();

        try {
            return $this->runController($request);
        } catch (HttpResponseException $e) {
            return $e->getResponse();
        }
    }

    /**
     * Process this method through a controller.
     *
     * @param Request $request
     *
     * @return mixed
     */
    protected function runController(Request $request)
    {
        $class = $this->controllerClassName;
        $method = $this->controllerMethodName;

        $parameters = $this->resolveClassMethodDependencies(
            [],
            $class,
            $method
        );

        $instance = $this->container->make($class);

        if (!method_exists($instance, $method)) {
            throw new NotFoundHttpException();
        }

        return call_user_func_array([$instance, $method], $parameters);
    }

    /**
     * Set whether or not the method should be hidden.
     *
     * @param bool $hidden
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }
}
