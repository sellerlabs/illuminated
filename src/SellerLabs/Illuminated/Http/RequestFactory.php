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

use Illuminate\Http\Request;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Http\Enums\HttpMethods;
use SellerLabs\Nucleus\Meditation\Arguments;
use SellerLabs\Nucleus\Meditation\Boa;
use SellerLabs\Nucleus\Meditation\Exceptions\InvalidArgumentException;
use Symfony\Component\HttpFoundation\HeaderBag;

/**
 * Class RequestFactory.
 *
 * This is a small factory class for building Laravel/Symfony requests by hand.
 * Its main purpose is to simplify testing by not having to run everything
 * through an HTTP kernel. Instead, the controller method is called directly.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Http
 */
class RequestFactory extends BaseObject
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var array
     */
    protected $request;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var array
     */
    protected $cookies;

    /**
     * @var array
     */
    protected $files;

    /**
     * @var array
     */
    protected $server;

    /**
     * @var array
     */
    protected $query;

    /**
     * @var string
     */
    protected $content;

    /**
     * Construct an instance of a RequestFactory.
     */
    public function __construct()
    {
        parent::__construct();

        $this->method = HttpMethods::GET;
        $this->headers = [];
        $this->request = [];
        $this->query = [];
        $this->uri = '/';
        $this->parameters = [];
        $this->cookies = [];
        $this->files = [];
        $this->server = [];
        $this->content = '';
    }

    /**
     * @return static
     */
    public static function define()
    {
        return new static();
    }

    /**
     * Set the request URI.
     *
     * @param $uri
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    public function onUri($uri)
    {
        Arguments::define(Boa::string())->check($uri);

        $this->uri = $uri;

        return $this;
    }

    /**
     * Set a header in the request.
     *
     * @param $key
     * @param $value
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    public function withHeader($key, $value)
    {
        Arguments::define(Boa::string(), Boa::string())
            ->check($key, $value);

        $key = strtolower($key);

        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Set the HTTP method using the request.
     *
     * @param $method
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    public function usingMethod($method)
    {
        Arguments::define(Boa::in(HttpMethods::getValues()))->check($method);

        $this->method = $method;

        return $this;
    }

    /**
     * Set the JSON body of the request.
     *
     * @param array $content
     *
     * @return $this
     */
    public function withJson(array $content)
    {
        $this->headers['content-type'] = 'application/json';
        $this->request = $content;
        $this->content = json_encode($content);

        return $this;
    }

    /**
     * Set the query of the request.
     *
     * @param array $query
     *
     * @return $this
     */
    public function withQuery(array $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Build the request instance.
     *
     * @return Request
     */
    public function make()
    {
        $request = new Request(
            $this->query,
            $this->request,
            $this->parameters,
            $this->cookies,
            $this->files,
            $this->server,
            $this->content
        );

        $request->headers = new HeaderBag($this->headers);

        return $request;
    }
}
