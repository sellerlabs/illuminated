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
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use SellerLabs\Illuminated\Auth\Interfaces\KeyPairFinderInterface;
use SellerLabs\Illuminated\Auth\KeyPairTypes;
use SellerLabs\Illuminated\Auth\Models\KeyPair;
use SellerLabs\Illuminated\Http\ApiResponse;
use SellerLabs\Illuminated\Http\Interfaces\Middleware;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Hashing\HmacHasher;
use SellerLabs\Nucleus\Meditation\Constraints\PrimitiveTypeConstraint;
use SellerLabs\Nucleus\Meditation\Primitives\ScalarTypes;
use SellerLabs\Nucleus\Meditation\Spec;

/**
 * Class HmacMiddleware.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Auth\Middleware
 */
class HmacMiddleware extends BaseObject implements Middleware
{
    const ATTRIBUTE_KEYPAIR = 'illuminated_hmac_keypair';

    protected $finder;

    protected $format;

    /**
     * Construct an instance of a HmacMiddleware.
     *
     * @param KeyPairFinderInterface $finder
     */
    public function __construct(KeyPairFinderInterface $finder)
    {
        parent::__construct();

        $this->finder = $finder;
        $this->format = 'Y-m-d H:i';
    }

    /**
     * Set format for the date/time used in the hash computation.
     *
     * Use DateTime::format() formatting.
     *
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validationResult = Spec::define(
            [
                'content-hash' => PrimitiveTypeConstraint::forType(
                    ScalarTypes::SCALAR_STRING
                ),
                'authorization' => PrimitiveTypeConstraint::forType(
                    ScalarTypes::SCALAR_STRING
                ),
            ], [], ['content-hash', 'authorization']
        )->check(array_map(function ($entry) {
            return $entry[0];
        }, $request->headers->all()));

        if ($validationResult->failed()) {
            return ApiResponse::makeFromSpec($validationResult)->toResponse();
        }

        $authorization = str_replace(
            'Hash ',
            '',
            $request->headers->get('Authorization')
        );
        $content = $request->getContent();

        try {
            $pair = $this->finder->byPublicId(
                $authorization,
                KeyPairTypes::TYPE_HMAC
            );

            $hasher = new HmacHasher();
            $verificationResult = $hasher->verify(
                $request->headers->get('Content-Hash'),
                $content . Carbon::now()->format($this->format),
                $pair->getSecretKey()
            );

            if ($verificationResult) {
                $request->attributes->set(static::ATTRIBUTE_KEYPAIR, $pair);

                return $next($request);
            }

            return ApiResponse::create([], ApiResponse::STATUS_INVALID, [
                'HMAC content hash does not match the expected hash.',
            ])->toResponse();
        } catch (ModelNotFoundException $ex) {
            if ($ex->getModel() === KeyPair::class) {
                return ApiResponse::create([], ApiResponse::STATUS_INVALID, [
                    'Unable to locate public ID. Check your credentials',
                ])->toResponse();
            }

            throw $ex;
        }
    }
}
