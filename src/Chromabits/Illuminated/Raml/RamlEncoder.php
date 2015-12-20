<?php

namespace Chromabits\Illuminated\Raml;

use Chromabits\Illuminated\Foundation\ApplicationManifest;
use Chromabits\Illuminated\Http\ApiCheckableRequest;
use Chromabits\Illuminated\Http\Factories\ResourceFactory;
use Chromabits\Illuminated\Http\Interfaces\AnnotatedControllerInterface;
use Chromabits\Illuminated\Http\ResourceReflector;
use Chromabits\Illuminated\Json\SpecSchemaEncoder;
use Chromabits\Illuminated\Raml\Interfaces\RamlEncoderInterface;
use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Http\Enums\HttpMethods;
use Chromabits\Nucleus\Meditation\Constraints\InArrayConstraint;
use Chromabits\Nucleus\Meditation\Constraints\PrimitiveTypeConstraint;
use Chromabits\Nucleus\Meditation\Primitives\ScalarTypes;
use Chromabits\Nucleus\Meditation\Spec;
use Chromabits\Nucleus\Support\Arr;
use Chromabits\Nucleus\Validation\Validator;
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
     *
     * @return mixed
     */
    public function encode(ApplicationManifest $manifest)
    {
        $root = [
            'title' => $manifest->getName(),
            'version' => $manifest->getCurrentVersion(),
            'mediaType' => 'application/json',
        ];

        if (count($manifest->getProse())) {
            foreach ($manifest->getProse() as $title => $content) {
                $root['documentation'][] = [
                    'title' => $title,
                    'content' => $content,
                ];
            }
        }

        foreach ($manifest->getApiResources() as $resource) {
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
            $ramlAction  = [];

            if ($controller instanceof AnnotatedControllerInterface) {
                $ramlAction['description'] = $controller->getMethodDescription(
                    $method->getMethod()
                );
            } else {
                $ramlAction['description']
                    = 'This method does not provide a description';
            }

            $reflector = new ResourceReflector($this->app);
            $request = $reflector->getMethodRequest($resource, $method);
            $uriParameters = [];
            $queryParameters = [];

            if ($request instanceof ApiCheckableRequest) {
                $spec = $request->getCheckable();

                if ($spec instanceof Validator) {
                    $spec = $spec->getSpec();
                }

                if ($spec instanceof Spec) {
                    if ($method->getVerb() == HttpMethods::POST
                        || $method->getVerb() == HttpMethods::PUT
                        || $method->getVerb() == HttpMethods::DELETE
                    ) {
                        $ramlAction['body'] = [
                            'schema' => (new SpecSchemaEncoder())
                                ->encode($spec),
                        ];
                    } else {
                        $parameters = $reflector->getMethodParameters(
                            $resource,
                            $method
                        );

                        $fields = array_unique(array_merge(
                            array_keys($spec->getConstraints()),
                            array_keys($spec->getDefaults()),
                            $spec->getRequired()
                        ));

                        foreach ($fields as $field) {
                            if (in_array($field, $parameters)) {
                                $uriParameters[$field]
                                    = $this->specFieldToParameter(
                                    $spec,
                                    $field
                                );

                                continue;
                            }

                            $queryParameters[$field]
                                = $this->specFieldToParameter(
                                $spec,
                                $field
                            );
                        }
                    }
                }
            }

            if (!Arr::has($ramlResource, $method->getVerb())) {
                $ramlResource[$method->getPath()] = [];
            }

            if (count($uriParameters)) {
                $ramlResource[$method->getPath()]['uriParameters']
                    = $uriParameters;
            }

            if (count($queryParameters)) {
                $ramlAction['queryParameters'] = $queryParameters;
            }

            $verb = strtolower($method->getVerb());
            $ramlResource[$method->getPath()][$verb] = $ramlAction;
        }

        return $ramlResource;
    }

    /**
     * Get a RAML parameter definition from a Spec field.
     *
     * @param Spec $spec
     * @param string $field
     *
     * @return array
     */
    protected function specFieldToParameter(Spec $spec, $field)
    {
        $constraints = $spec->getConstraints();
        $defaults = $spec->getDefaults();
        $required = $spec->getRequired();

        $parameter = [];

        if (Arr::has($constraints, $field)) {
            $input = $constraints[$field];

            if (is_array($input) || $input instanceof Spec) {
                if ($input instanceof Spec) {
                    $input = [$input];
                }

                foreach ($input as $constraint) {
                    if ($constraint instanceof PrimitiveTypeConstraint) {
                        switch ($constraint->toString()) {
                            case ScalarTypes::SCALAR_STRING:
                                $parameter['type'] = 'string';
                                break;
                            case ScalarTypes::SCALAR_FLOAT:
                            case ScalarTypes::SCALAR_INTEGER:
                                $parameter['type'] = 'number';
                                break;
                            case ScalarTypes::SCALAR_BOOLEAN:
                                $parameter['type'] = 'boolean';
                                break;
                        }
                    }
                }
            }
        }

        if (Arr::has($defaults, $field)) {
            $parameter['default'] = $defaults[$field];
        }

        $parameter['required'] = in_array($field, $required);

        return $parameter;
    }
}