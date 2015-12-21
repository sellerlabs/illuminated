<?php

namespace Chromabits\Illuminated\Raml;

use Chromabits\Illuminated\Raml\Security\OAuth2Scheme;
use Chromabits\Illuminated\Raml\Security\SecurityScheme;
use Chromabits\Nucleus\Foundation\BaseObject;

/**
 * Class RamlEncoderOptions.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Raml
 */
class RamlEncoderOptions extends BaseObject
{
    /**
     * @var SecurityScheme[]
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
            ->setSecuritySchemes([
                ['oauth_2_0' => new OAuth2Scheme()],
            ]);
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
        $this->securitySchemes = $securitySchemes;

        return $this;
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
        $this->middlewareToSchemeMapping = $middlewareToSchemeMapping;

        return $this;
    }
}