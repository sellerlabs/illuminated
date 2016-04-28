<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Auth;

use Carbon\Carbon;
use SellerLabs\Illuminated\Auth\Models\KeyPair;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Hashing\HmacHasher;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HmacSigner.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Auth
 */
class HmacSigner extends BaseObject
{
    /**
     * @var KeyPair
     */
    protected $keyPair;

    /**
     * @var HmacHasher
     */
    protected $hasher;

    /**
     * Construct an instance of a HmacSigner.
     *
     * @param KeyPair $keyPair
     * @param HmacHasher $hasher
     *
     * @throws \SellerLabs\Nucleus\Exceptions\LackOfCoffeeException
     */
    public function __construct(KeyPair $keyPair, HmacHasher $hasher = null)
    {
        parent::__construct();

        $this->keyPair = $keyPair;
        $this->hasher = $hasher === null ? new HmacHasher() : $hasher;
    }

    /**
     * Sing the request using HMAC.
     *
     * @param Request $request
     *
     * @return Request
     */
    public function sign(Request $request)
    {
        $contentHash = $this->hash($request->getContent());

        $request = clone $request;

        $request->headers->set('Content-Hash', $contentHash);
        $request->headers->set(
            'Authorization',
            vsprintf('Hash %s', [$this->keyPair->public_id])
        );

        return $request;
    }

    /**
     * Generate the content hash using the content string and the current date.
     *
     * @param $content
     *
     * @return string
     */
    public function hash($content)
    {
        return $this->hasher->hash(
            $content . Carbon::now()->format('Y-m-d H:i'),
            $this->keyPair->secret_key
        );
    }
}
