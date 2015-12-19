<?php

namespace Chromabits\Illuminated\Raml\Controllers;

use Chromabits\Illuminated\Foundation\ApplicationManifest;
use Chromabits\Illuminated\Http\BaseController;
use Chromabits\Illuminated\Raml\Interfaces\RamlEncoderInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RamlController.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Raml\Controllers
 */
class RamlController extends BaseController
{
    /**
     * Generate and return a RAML file describing the current application
     * manifest.
     *
     * @param RamlEncoderInterface $ramlEncoder
     * @param ApplicationManifest $manifest
     *
     * @return Response
     */
    public function getIndex(
        RamlEncoderInterface $ramlEncoder,
        ApplicationManifest $manifest
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