<?php

namespace Chromabits\Illuminated\Conference;

use Chromabits\Nucleus\Exceptions\LackOfCoffeeException;
use Chromabits\Nucleus\Foundation\BaseObject;
use Illuminate\Container\Container;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteDependencyResolverTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Method extends BaseObject
{
    use RouteDependencyResolverTrait;

    protected $name;

    protected $controllerClassName;

    protected $controllerMethodName;

    protected $verb;

    protected $label;

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
     * @var Container
     */
    protected $container;

    /**
     * Construct an instance of a Method.
     *
     * @param $name
     * @param $controllerClassName
     * @param $controllerMethodName
     * @param string $verb
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
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    public function run(Request $request)
    {
        $this->container = $this->container ?: new Container();

        try {
            return $this->runController($request);
        } catch (HttpResponseException $e) {
            return $e->getResponse();
        }
    }

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
}