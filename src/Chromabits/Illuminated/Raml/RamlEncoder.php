<?php

namespace Chromabits\Illuminated\Raml;

use Chromabits\Illuminated\Foundation\ApplicationManifest;
use Chromabits\Illuminated\Http\Factories\ResourceFactory;
use Chromabits\Illuminated\Http\Interfaces\AnnotatedControllerInterface;
use Chromabits\Illuminated\Raml\Interfaces\RamlEncoderInterface;
use Chromabits\Nucleus\Foundation\BaseObject;
use Illuminate\Contracts\Container\Container;

/**
 * Class RamlEncoder.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Raml
 */
class RamlEncoder extends BaseObject implements RamlEncoderInterface
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * Construct an instance of a RamlEncoder.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct();

        $this->app = $container;
    }

    /**
     * Generate a raml file describing the application.
     *
     * @param ApplicationManifest $manifest
     */
    public function encode(ApplicationManifest $manifest)
    {
        $root = [
            'title' => $manifest->getName(),
            'version' => $manifest->getCurrentVersion(),
        ];

        if (count($manifest->getProse())) {
            foreach ($manifest->getProse() as $title => $content) {
                $root['documentation'] = [
                    'title' => $title,
                    'content' => $content,
                ];
            }
        }

        foreach ($manifest->getResources() as $resource) {
            $path = $resource->getPrefix();

            if ($path == '') {
                $path = '/';
            }

            $root[$path] = $this
                ->encodeResource($resource);
        }

        $yaml = yaml_emit($root);

        return str_replace("---\n", "#%RAML 0.8\n", $yaml);
    }

    protected function encodeResource(ResourceFactory $resource)
    {
        $controller = $this->app->make($resource->getController());

        $ramlResource  = [
            'displayName' => $resource->getName(),
            'description' => $resource->getDescription(),
        ];

        foreach ($resource->getMethods() as $method) {
            $ramlAction  = [
                'displayName' => $method->getMethod(),
            ];

            if ($controller instanceof AnnotatedControllerInterface) {
                $ramlAction['displayName'] = $controller->getMethodName(
                    $method->getMethod()
                );
                $ramlAction['description'] = $controller->getMethodDescription(
                    $method->getMethod()
                );
            }

            $ramlResource[$method->getPath()] = $ramlAction;
        }

        return $ramlResource;
    }
}