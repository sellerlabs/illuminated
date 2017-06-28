<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Raml\Controllers;

use SellerLabs\Illuminated\Foundation\Interfaces\ApplicationManifestInterface;
use SellerLabs\Illuminated\Http\BaseController;
use SellerLabs\Illuminated\Raml\Interfaces\RamlEncoderInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RamlController.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Raml\Controllers
 */
class RamlController extends BaseController
{
    /**
     * Generate and return a RAML file describing the current application
     * manifest.
     *
     * @param RamlEncoderInterface $ramlEncoder
     * @param ApplicationManifestInterface $manifest
     *
     * @return Response
     */
    public function getIndex(
        RamlEncoderInterface $ramlEncoder,
        ApplicationManifestInterface $manifest
    ) {
        return Response::create(
            $ramlEncoder->encode($manifest),
            Response::HTTP_OK,
            [
                'content-type' => 'application/yaml',
            ]
        );
    }
}
