<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Raml\Interfaces;

use SellerLabs\Illuminated\Foundation\Interfaces\ApplicationManifestInterface;
use SellerLabs\Illuminated\Raml\RamlEncoderOptions;

/**
 * Interface RamlEncoderInterface.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Raml\Interfaces
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
