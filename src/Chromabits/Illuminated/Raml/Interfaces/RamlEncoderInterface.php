<?php

namespace Chromabits\Illuminated\Raml\Interfaces;

use Chromabits\Illuminated\Foundation\ApplicationManifest;
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
     * @param ApplicationManifest $manifest
     * @param RamlEncoderOptions $options
     *
     * @return string
     */
    public function encode(
        ApplicationManifest $manifest,
        RamlEncoderOptions $options
    );
}