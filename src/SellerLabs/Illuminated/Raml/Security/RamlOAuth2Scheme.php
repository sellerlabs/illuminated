<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Raml\Security;

use SellerLabs\Illuminated\Raml\Enums\RamlTypes;
use SellerLabs\Illuminated\Raml\RamlBody;
use SellerLabs\Illuminated\Raml\RamlMessageBody;
use SellerLabs\Illuminated\Raml\RamlParameter;
use SellerLabs\Illuminated\Raml\RamlResponse;
use SellerLabs\Illuminated\Raml\RamlResponseGroup;

/**
 * Class OAuth2Scheme.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Raml\Security
 */
class RamlOAuth2Scheme extends RamlSecurityScheme
{
    /**
     * Construct an instance of a OAuth2Scheme.
     */
    public function __construct()
    {
        parent::__construct();

        $this->description = 'This API supports OAuth 2.0 for authenticating ' .
            'requests.';
        $this->type = 'OAuth 2.0';

        $this->headers = [
            'Authorization' => (new RamlParameter())
                ->setDescription('Used to send a valid OAuth 2 access token.')
                ->setType(RamlTypes::TYPE_STRING),
        ];

        $this->responses = (new RamlResponseGroup())
            ->addResponse(
                401,
                (new RamlResponse())->setDescription(
                    'Bad or expired token. This can happen if the user or ' .
                    'the API revoked or expired an access token. To fix, you ' .
                    'should re-authenticate the user.'
                )
            )
            ->addResponse(
                403,
                (new RamlResponse())
                    ->setDescription(
                        'Bad OAuth request (wrong consumer key, bad nonce, ' .
                        'expired timestamp...). Unfortunately, ' .
                        're-authenticating the user won\'t help here.'
                    )
                    ->setBody(
                        (new RamlMessageBody())
                            ->addType(
                                'application/json',
                                (new RamlBody())
                                    ->setExample(
                                        json_encode(
                                            [
                                                'code' => '403',
                                                'status' => 'forbidden',
                                                'messages' => [
                                                    'Authorization is required',
                                                ],
                                            ]
                                        )
                                    )
                            )
                    )
            );

        $this->settings = [
            'authorizationUri' => 'https://localhost/oauth/auth',
            'accessTokenUri' => 'https://localhost/oauth/token',
            'authorizationGrants' => ['code'],
            'scopes' => ['global'],
        ];
    }
}
