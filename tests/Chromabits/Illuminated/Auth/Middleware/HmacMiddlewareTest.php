<?php

namespace Chromabits\Illuminated\Auth\Middleware;

use Chromabits\Illuminated\Auth\Interfaces\KeyPairFinderInterface;
use Chromabits\Illuminated\Auth\KeyPairGenerator;
use Chromabits\Illuminated\Auth\KeyPairTypes;
use Chromabits\Illuminated\Auth\Models\KeyPair;
use Chromabits\Nucleus\Hashing\HmacHasher;
use Chromabits\Nucleus\Support\Std;
use Chromabits\Nucleus\Testing\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Mockery as m;

/**
 * Class HmacMiddlewareTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth\Middleware
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
            $hasher->hash($content, $pair->getSecretKey())
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
                $body['messages'][0]
            ]
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
                $body['messages'][0]
            ],
            [['content-hash'], $body['missing']]
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
                $body['messages'][0]
            ],
            [['authorization'], $body['missing']]
        ]);
    }
}
