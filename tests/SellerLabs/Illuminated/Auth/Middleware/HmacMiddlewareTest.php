<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Auth\Middleware;

use Carbon\Carbon;
use Mockery as m;
use SellerLabs\Illuminated\Auth\Interfaces\KeyPairFinderInterface;
use SellerLabs\Illuminated\Auth\KeyPairGenerator;
use SellerLabs\Illuminated\Auth\KeyPairTypes;
use SellerLabs\Illuminated\Auth\Models\KeyPair;
use SellerLabs\Nucleus\Hashing\HmacHasher;
use SellerLabs\Nucleus\Support\Std;
use SellerLabs\Nucleus\Testing\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HmacMiddlewareTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Auth\Middleware
 */
class HmacMiddlewareTest extends TestCase
{
    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        KeyPair::registerEvents();
    }

    public function testHandle()
    {
        $generator = new KeyPairGenerator();
        $hasher = new HmacHasher();
        $pair = $generator->generateHmac();

        $content = json_encode([
            'doge' => 'much secret',
        ]);

        $validRequest = Request::create(
            '/auth/something/important/dont/hax/pls',
            'POST',
            [],
            [],
            [],
            [],
            $content
        );

        $validRequest->headers->set(
            'Content-Hash',
            $hasher->hash(
                $content . Carbon::now()->format('Y-m-d H:i'),
                $pair->getSecretKey()
            )
        );
        $validRequest->headers->set(
            'Authorization',
            'Hash ' . $pair->getPublicId()
        );

        $finder = m::mock(KeyPairFinderInterface::class);
        $finder->shouldReceive('byPublicId')
            ->with($pair->getPublicId(), KeyPairTypes::TYPE_HMAC)
            ->once()
            ->andReturn($pair);

        $instance = new HmacMiddleware($finder);

        $called = null;
        $response = $instance->handle(
            $validRequest,
            function ($nextRequest) use (&$called) {
                $called = $nextRequest;

                return 'ok';
            }
        );

        $this->assertEquals($validRequest, $called);
        $this->assertEquals('ok', $response);
        $this->assertEquals(
            $pair,
            $called->attributes->get(HmacMiddleware::ATTRIBUTE_KEYPAIR)
        );
    }

    public function testHandleWithWrongHash()
    {
        $generator = new KeyPairGenerator();
        $pair = $generator->generateHmac();

        $content = json_encode([
            'doge' => 'much secret',
        ]);

        $brokenRequest = Request::create(
            '/auth/something/important/dont/hax/pls',
            'POST',
            [],
            [],
            [],
            [],
            $content
        );

        $brokenRequest->headers->set(
            'Content-Hash',
            'nope'
        );
        $brokenRequest->headers->set(
            'Authorization',
            'Hash ' . $pair->getPublicId()
        );

        $finder = m::mock(KeyPairFinderInterface::class);
        $finder->shouldReceive('byPublicId')
            ->with($pair->getPublicId(), KeyPairTypes::TYPE_HMAC)
            ->once()
            ->andReturn($pair);

        $instance = new HmacMiddleware($finder);

        $response = $instance->handle($brokenRequest, function () {
            //
        });

        $body = Std::jsonDecode($response->getContent());
        $this->assertEqualsMatrix([
            [
                'HMAC content hash does not match the expected hash.',
                $body['messages'][0],
            ],
        ]);
    }

    public function testHandleWithMissingHash()
    {
        $generator = new KeyPairGenerator();
        $pair = $generator->generateHmac();

        $content = json_encode([
            'doge' => 'much secret',
        ]);

        $brokenRequest = Request::create(
            '/auth/something/important/dont/hax/pls',
            'POST',
            [],
            [],
            [],
            [],
            $content
        );

        $brokenRequest->headers->set(
            'Authorization',
            'Hash ' . $pair->getPublicId()
        );

        $finder = m::mock(KeyPairFinderInterface::class);
        $finder->shouldReceive('byPublicId')
            ->with($pair->getPublicId(), KeyPairTypes::TYPE_HMAC)
            ->once()
            ->andReturn($pair);

        $instance = new HmacMiddleware($finder);

        $response = $instance->handle($brokenRequest, function () {
            //
        });

        $body = Std::jsonDecode($response->getContent());
        $this->assertEqualsMatrix([
            [
                'One or more fields are invalid. Please check your input.',
                $body['messages'][0],
            ],
            [['content-hash'], $body['missing']],
        ]);
    }

    public function testHandleWithMissingAuthorization()
    {
        $generator = new KeyPairGenerator();
        $hasher = new HmacHasher();
        $pair = $generator->generateHmac();

        $content = json_encode([
            'doge' => 'much secret',
        ]);

        $brokenRequest = Request::create(
            '/auth/something/important/dont/hax/pls',
            'POST',
            [],
            [],
            [],
            [],
            $content
        );

        $brokenRequest->headers->set(
            'Content-Hash',
            $hasher->hash($content, $pair->getSecretKey())
        );

        $finder = m::mock(KeyPairFinderInterface::class);
        $finder->shouldReceive('byPublicId')
            ->with($pair->getPublicId(), KeyPairTypes::TYPE_HMAC)
            ->once()
            ->andReturn($pair);

        $instance = new HmacMiddleware($finder);

        $response = $instance->handle($brokenRequest, function () {
            //
        });

        $body = Std::jsonDecode($response->getContent());
        $this->assertEqualsMatrix([
            [
                'One or more fields are invalid. Please check your input.',
                $body['messages'][0],
            ],
            [['authorization'], $body['missing']],
        ]);
    }
}
