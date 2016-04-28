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

use SellerLabs\Illuminated\Http\Factories\ResourceFactory;
use SellerLabs\Illuminated\Http\ResourceAggregator;
use SellerLabs\Illuminated\Raml\Controllers\RamlController;

/**
 * Class RamlResourceAggregator.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Raml
 */
class RamlResourceAggregator extends ResourceAggregator
{
    /**
     * Get resources to be aggregated.
     *
     * @return ResourceFactory[]
     */
    public function getResources()
    {
        return [
            ResourceFactory::create(RamlController::class)
                ->get('/api.raml', 'getIndex'),
        ];
    }
}
