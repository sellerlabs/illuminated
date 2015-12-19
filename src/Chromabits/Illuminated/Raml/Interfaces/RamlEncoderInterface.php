<?php

namespace Chromabits\Illuminated\Raml\Interfaces;

use Chromabits\Illuminated\Foundation\ApplicationManifest;

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
     */
    public function encode(ApplicationManifest $manifest);
}