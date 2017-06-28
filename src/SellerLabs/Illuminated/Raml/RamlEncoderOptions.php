<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Raml;

use SellerLabs\Illuminated\Raml\Security\RamlOAuth2Scheme;
use SellerLabs\Illuminated\Raml\Security\RamlSecurityScheme;
use SellerLabs\Nucleus\Foundation\BaseObject;

/**
 * Class RamlEncoderOptions.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Raml
 */
class RamlEncoderOptions extends BaseObject
{
    /**
     * @var RamlSecurityScheme[]
     */
    protected $securitySchemes;

    /**
     * @var string[]
     */
    protected $middlewareToSchemeMapping;

    /**
     * @return RamlEncoderOptions
     */
    public static function defaultOptions()
    {
        return (new RamlEncoderOptions())
            ->setMiddlewareToSchemeMapping([])
            ->setSecuritySchemes(
                [
                    ['oauth_2_0' => new RamlOAuth2Scheme()],
                ]
            );
    }

    /**
     * @return mixed
     */
    public function getSecuritySchemes()
    {
        return $this->securitySchemes;
    }

    /**
     * @param mixed $securitySchemes
     *
     * @return RamlEncoderOptions
     */
    public function setSecuritySchemes($securitySchemes)
    {
        $new = clone $this;

        $new->securitySchemes = $securitySchemes;

        return $new;
    }

    /**
     * @return mixed
     */
    public function getMiddlewareToSchemeMapping()
    {
        return $this->middlewareToSchemeMapping;
    }

    /**
     * @param mixed $middlewareToSchemeMapping
     *
     * @return RamlEncoderOptions
     */
    public function setMiddlewareToSchemeMapping($middlewareToSchemeMapping)
    {
        $new = clone $this;

        $new->middlewareToSchemeMapping = $middlewareToSchemeMapping;

        return $new;
    }
}
