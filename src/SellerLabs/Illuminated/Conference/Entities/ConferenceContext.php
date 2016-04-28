<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Conference\Entities;

use Illuminate\Session\SessionManager;
use SellerLabs\Nucleus\Exceptions\LackOfCoffeeException;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Support\Std;

/**
 * Class ConferenceContext.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Entities
 */
class ConferenceContext extends BaseObject
{
    const SESSION_LAST_MODULE = 'illuminated.conference.lastModule';
    const SESSION_LAST_METHOD = 'illuminated.conference.lastMethod';
    const SESSION_LAST_PARAMETERS = 'illuminated.conference.lastParameters';

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var SessionManager
     */
    protected $session;

    /**
     * Construct an instance of a ConferenceContext.
     *
     * @param string $basePath
     * @param SessionManager $session
     *
     * @throws LackOfCoffeeException
     */
    public function __construct($basePath, SessionManager $session)
    {
        parent::__construct();

        $this->basePath = $basePath;
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Generate a URL for a dashboard module.
     *
     * Note: If the module does not have a default method, then the user will
     * see an error page.
     *
     * @param string $moduleName
     * @param array $parameters
     * @param null|bool $secure
     *
     * @return string
     */
    public function module($moduleName, $parameters = [], $secure = null)
    {
        return $this->url('/' . $moduleName . '/', $parameters, $secure);
    }

    /**
     * Generate a URL for a path inside the dashboard.
     *
     * @param string $path
     * @param array $parameters
     * @param null|bool $secure
     *
     * @return string
     */
    public function url($path = '', $parameters = [], $secure = null)
    {
        $queryString = '';

        if (count($parameters)) {
            $queryString = '?' . implode('&', Std::map(
                    function ($value, $name) {
                        return urlencode($name) . '=' . urlencode($value);
                    }, $parameters
                ));
        }

        return url($this->basePath . $path, [], $secure) . $queryString;
    }

    /**
     * Generate a URL for a dashboard module method.
     *
     * @param string $moduleName
     * @param string $methodName
     * @param array $parameters
     * @param null|bool $secure
     *
     * @return string
     */
    public function method(
        $moduleName,
        $methodName,
        $parameters = [],
        $secure = null
    ) {
        return $this->url(
            '/' . $moduleName . '/' . $methodName . '/',
            $parameters,
            $secure
        );
    }

    /**
     * Get the last used module/method in this session.
     *
     * @param bool|null $secure
     *
     * @return string
     */
    public function lastUrl($secure = null)
    {
        if ($this->session->has(static::SESSION_LAST_MODULE)
            && $this->session->has(static::SESSION_LAST_METHOD)) {
            return $this->method(
                $this->session->get(static::SESSION_LAST_MODULE),
                $this->session->get(static::SESSION_LAST_METHOD),
                $this->session->get(static::SESSION_LAST_PARAMETERS, []),
                $secure
            );
        } elseif ($this->session->has(static::SESSION_LAST_MODULE)) {
            return $this->module(
                $this->session->get(static::SESSION_LAST_MODULE),
                $this->session->get(static::SESSION_LAST_PARAMETERS, []),
                $secure
            );
        }

        return $this->url(
            '',
            $this->session->get(static::SESSION_LAST_PARAMETERS, []),
            $secure
        );
    }

    /**
     * Forget the last module/method URL.
     */
    public function clearLastUrl()
    {
        $this->session->forget(
            ConferenceContext::SESSION_LAST_MODULE
        );
        $this->session->forget(
            ConferenceContext::SESSION_LAST_METHOD
        );
        $this->session->forget(
            ConferenceContext::SESSION_LAST_PARAMETERS
        );
    }

    /**
     * Set the last used module/method.
     *
     * @param string $module
     * @param string|null $method
     * @param array|null $parameters
     */
    public function setLastUrl($module, $method = null, $parameters = null)
    {
        $this->session->set(
            ConferenceContext::SESSION_LAST_MODULE,
            $module
        );

        if ($method !== null) {
            $this->session->set(
                ConferenceContext::SESSION_LAST_METHOD,
                $method
            );
        }

        if ($parameters !== null) {
            $this->session->set(
                ConferenceContext::SESSION_LAST_PARAMETERS,
                $parameters
            );
        }
    }
}
