<?php

namespace Chromabits\Illuminated\Raml\Interfaces;

use Chromabits\Illuminated\Foundation\Interfaces\ApplicationManifestInterface;
use Chromabits\Illuminated\Raml\RamlEncoderOptions;

/**
 * Interface RamlEncoderInterface.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Raml\Interfaces
 */
interface RamlEncoderInterface
{
    /**
     * Generate a raml file describing the application.
     *
     * @param ApplicationManifestInterface $manifest
     * @param RamlEncoderOptions $options
     *
     * @return string
     */
    public function encode(
        ApplicationManifestInterface $manifest,
        RamlEncoderOptions $options = null
    );
}