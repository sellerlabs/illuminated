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

use SellerLabs\Illuminated\Conference\Entities\ConferenceContext;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Support\Arr;
use SellerLabs\Nucleus\Support\Html;
use SellerLabs\Nucleus\Support\Std;
use SellerLabs\Nucleus\View\Common\Anchor;
use SellerLabs\Nucleus\View\Common\ListItem;
use SellerLabs\Nucleus\View\Common\UnorderedList;
use SellerLabs\Nucleus\View\SafeHtmlWrapper;

/**
 * Class Module.
 *
 * @todo
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference
 */
abstract class Module extends BaseObject
{
    /**
     * @var Method[]
     */
    protected $methods;

    /**
     * @var string
     */
    protected $icon;

    /**
     * Construct an instance of a Module.
     */
    public function __construct()
    {
        parent::__construct();

        $this->methods = [];
        $this->icon = 'fa-question-circle';
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Boot the module.
     */
    public function boot()
    {
        //
    }

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return string
     */
    abstract public function getLabel();

    /**
     * @return string
     */
    abstract public function getDescription();

    /**
     * Get the name of the default method.
     *
     * @return string|null
     */
    abstract public function getDefaultMethodName();

    /**
     * Return whether or not a method exists in this module.
     *
     * @param string $methodName
     *
     * @return bool
     */
    public function hasMethod($methodName)
    {
        return Arr::has($this->getMethods(), $methodName);
    }

    /**
     * Get the methods available in this module.
     *
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Get a method in this module by its name.
     *
     * @param string $methodName
     *
     * @return mixed
     */
    public function getMethod($methodName)
    {
        return Arr::dotGet($this->getMethods(), $methodName);
    }

    /**
     * Render the sidebar for this module.
     *
     * If null is returned, we won't display one.
     *
     * @param ConferenceContext $context
     *
     * @return SafeHtmlWrapper
     */
    public function renderSidebar(ConferenceContext $context)
    {
        return Html::safe((new UnorderedList(
            ['class' => 'nav nav-pills nav-stacked'],
            Std::map(function (Method $method, $methodName) use ($context) {
                return new ListItem([
                    'class' => 'nav-item',
                ], [
                    new Anchor([
                        'href' => $context->method(
                            $this->getName(),
                            $methodName
                        ),
                        'class' => 'nav-link',
                    ], Std::coalesce($method->getLabel(), $methodName)),
                ]);
            }, Std::filter(function (Method $method) {
                return !$method->isHidden();
            }, $this->getMethods()))
        ))->render());
    }

    /**
     * Register a method on this module.
     *
     * @param string $name
     * @param string $controllerClassName
     * @param string $controllerMethodName
     * @param null|string $label
     * @param string $verb
     * @param bool $hidden
     */
    protected function register(
        $name,
        $controllerClassName,
        $controllerMethodName,
        $label = null,
        $verb = 'GET',
        $hidden = false
    ) {
        $method = new Method(
            $name,
            $controllerClassName,
            $controllerMethodName,
            $verb
        );

        if ($label !== null) {
            $method->setLabel($label);
        }

        if ($hidden) {
            $method->setHidden(true);
        }

        $this->methods[$name] = $method;
    }
}
