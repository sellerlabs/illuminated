<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Testing;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use PHPUnit_Framework_Assert as PHPUnit;

/**
 * Class AssertionsTrait.
 *
 * @author Laravel/Lumen
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Testing
 */
trait AssertionsTrait
{
    /**
     * Assert that the client response has an OK status code.
     *
     */
    public function assertResponseOk()
    {
        $actual = $this->response->getStatusCode();

        PHPUnit::assertTrue(
            $this->response->isOk(),
            "Expected status code 200, got {$actual}."
        );
    }

    /**
     * Assert that the client response has a given code.
     *
     * @param  int $code
     *
     */
    public function assertResponseStatus($code)
    {
        $actual = $this->response->getStatusCode();

        PHPUnit::assertEquals(
            $code,
            $this->response->getStatusCode(),
            "Expected status code {$code}, got {$actual}."
        );
    }

    /**
     * Assert that the response view has a given piece of bound data.
     *
     * @param  string|array $key
     * @param  mixed $value
     *
     */
    public function assertViewHas($key, $value = null)
    {
        if (is_array($key)) {
            $this->assertViewHasAll($key);

            return;
        }

        if (!isset($this->response->original)
            || !$this->response->original instanceof View
        ) {
            PHPUnit::assertTrue(false, 'The response was not a view.');

            return;
        }
        if (is_null($value)) {
            PHPUnit::assertArrayHasKey(
                $key,
                $this->response->original->getData()
            );
        } else {
            PHPUnit::assertEquals($value, $this->response->original->$key);
        }
    }

    /**
     * Assert that the view has a given list of bound data.
     *
     * @param  array $bindings
     *
     */
    public function assertViewHasAll(array $bindings)
    {
        foreach ($bindings as $key => $value) {
            if (is_int($key)) {
                $this->assertViewHas($value);
            } else {
                $this->assertViewHas($key, $value);
            }
        }
    }

    /**
     * Assert that the response view is missing a piece of bound data.
     *
     * @param  string $key
     *
     */
    public function assertViewMissing($key)
    {
        if (!isset($this->response->original)
            || !$this->response->original instanceof View
        ) {
            PHPUnit::assertTrue(
                false,
                'The response was not a view.'
            );

            return;
        }

        PHPUnit::assertArrayNotHasKey(
            $key,
            $this->response->original->getData()
        );
    }

    /**
     * Assert whether the client was redirected to a given URI.
     *
     * @param  string $uri
     * @param  array $with
     *
     */
    public function assertRedirectedTo($uri, $with = [])
    {
        PHPUnit::assertInstanceOf(
            RedirectResponse::class,
            $this->response
        );

        PHPUnit::assertEquals(
            $this->app['url']->to($uri),
            $this->response->headers->get('Location')
        );

        $this->assertSessionHasAll($with);
    }

    /**
     * Assert whether the client was redirected to a given route.
     *
     * @param  string $name
     * @param  array $parameters
     * @param  array $with
     *
     */
    public function assertRedirectedToRoute($name, $parameters = [], $with = [])
    {
        $this->assertRedirectedTo(
            $this->app['url']->route($name, $parameters),
            $with
        );
    }

    /**
     * Assert that the session has a given list of values.
     *
     * @param  string|array $key
     * @param  mixed $value
     *
     */
    public function assertSessionHas($key, $value = null)
    {
        if (is_array($key)) {
            $this->assertSessionHasAll($key);

            return;
        }

        if (is_null($value)) {
            PHPUnit::assertTrue(
                $this->app['session.store']->has($key),
                "Session missing key: $key"
            );
        } else {
            PHPUnit::assertEquals(
                $value, $this->app['session.store']->get($key)
            );
        }
    }

    /**
     * Assert that the session has a given list of values.
     *
     * @param  array $bindings
     *
     */
    public function assertSessionHasAll(array $bindings)
    {
        foreach ($bindings as $key => $value) {
            if (is_int($key)) {
                $this->assertSessionHas($value);
            } else {
                $this->assertSessionHas($key, $value);
            }
        }
    }

    /**
     * Assert that the session has errors bound.
     *
     * @param  string|array $bindings
     * @param  mixed $format
     *
     */
    public function assertSessionHasErrors($bindings = [], $format = null)
    {
        $this->assertSessionHas('errors');
        $bindings = (array) $bindings;
        $errors = $this->app['session.store']->get('errors');
        foreach ($bindings as $key => $value) {
            if (is_int($key)) {
                PHPUnit::assertTrue(
                    $errors->has($value),
                    "Session missing error: $value"
                );
            } else {
                PHPUnit::assertContains($value, $errors->get($key, $format));
            }
        }
    }

    /**
     * Assert that the session has old input.
     *
     */
    public function assertHasOldInput()
    {
        $this->assertSessionHas('_old_input');
    }
}
