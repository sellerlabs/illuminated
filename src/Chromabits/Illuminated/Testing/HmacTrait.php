<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Testing;

use Chromabits\Illuminated\Auth\HmacSigner;
use Chromabits\Illuminated\Auth\Models\KeyPair;

/**
 * Class HmacTrait.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Testing
 */
trait HmacTrait
{
    /**
     * Visit the given URI with a JSON request.
     *
     * @param KeyPair $keyPair
     * @param  string $method
     * @param  string $uri
     * @param  array $data
     * @param  array $headers
     *
     * @return $this
     */
    public function jsonWithHmac(
        KeyPair $keyPair,
        $method,
        $uri,
        array $data = [],
        array $headers = []
    ) {
        $content = json_encode($data);

        $headers = array_merge([
            'CONTENT_LENGTH' => mb_strlen($content, '8bit'),
            'CONTENT_TYPE' => 'application/json',
            'Content-Hash' => (new HmacSigner($keyPair))->hash($content),
            'Authorization' => vsprintf('Hash %s', [$keyPair->public_id]),
            'Accept' => 'application/json',
        ], $headers);

        $this->call(
            $method,
            $uri,
            [],
            [],
            [],
            $this->transformHeadersToServerVars($headers),
            $content
        );

        return $this;
    }
}
