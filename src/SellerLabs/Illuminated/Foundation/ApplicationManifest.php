<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Foundation;

use Illuminate\Contracts\Routing\UrlGenerator;
use SellerLabs\Illuminated\Foundation\Interfaces\ApplicationManifestInterface;
use SellerLabs\Illuminated\Http\Factories\ResourceFactory;
use SellerLabs\Illuminated\Http\ResourceAggregator;
use SellerLabs\Illuminated\Http\RouteAggregator;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Support\Arr;
use SellerLabs\Nucleus\Support\Std;
use SellerLabs\Nucleus\Support\Str;

/**
 * Class ApplicationManifest.
 *
 * Declares information about a web application.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Foundation
 */
abstract class ApplicationManifest extends BaseObject implements
    ApplicationManifestInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $currentVersion;

    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;

    /**
     * Construct an instance of a ApplicationManifest.
     *
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        parent::__construct();

        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return RouteAggregator
     */
    abstract public function getRouteAggregator();

    /**
     * Get all the ResourceFactories for this application.
     *
     * @return ResourceFactory[]
     */
    public function getResources()
    {
        return Std::reduce(
            function (array $resources, $mapper) {
                if ($mapper instanceof ResourceFactory) {
                    return array_merge($resources, [$mapper]);
                } elseif ($mapper instanceof ResourceAggregator) {
                    return array_merge($resources, $mapper->getResources());
                }

                return $resources;
            },
            [],
            $this->getRouteAggregator()->getMapperInstances()
        );
    }

    /**
     * Get all the API ResourceFactories for this application.
     *
     * @return ResourceFactory[]
     */
    public function getApiResources()
    {
        return Std::filter(function (ResourceFactory $resource) {
            foreach ($this->getApiPrefixes() as $prefix) {
                if (Str::beginsWith($resource->getPrefix(), $prefix)) {
                    return true;
                }
            }

            return false;
        }, $this->getResources());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCurrentVersion()
    {
        return $this->currentVersion;
    }

    /**
     * @return string[]
     */
    public function getProse()
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getApiPrefixes()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->urlGenerator->to('/');
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return [];
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasProperty($key)
    {
        return Arr::has($this->getProperties(), $key);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getProperty($key)
    {
        return Arr::dotGet($this->getProperties(), $key);
    }
}
