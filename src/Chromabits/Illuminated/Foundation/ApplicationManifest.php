<?php

namespace Chromabits\Illuminated\Foundation;

use Chromabits\Illuminated\Http\Factories\ResourceFactory;
use Chromabits\Illuminated\Http\ResourceAggregator;
use Chromabits\Illuminated\Http\RouteAggregator;
use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Support\Std;
use Chromabits\Nucleus\Support\Str;

/**
 * Class ApplicationManifest.
 *
 * Declares information about a web application.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Foundation
 */
abstract class ApplicationManifest extends BaseObject
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
}