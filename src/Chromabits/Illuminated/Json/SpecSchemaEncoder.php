<?php

namespace Chromabits\Illuminated\Json;

use Chromabits\Nucleus\Exceptions\CoreException;
use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Meditation\Constraints\AbstractConstraint;
use Chromabits\Nucleus\Meditation\Constraints\PrimitiveTypeConstraint;
use Chromabits\Nucleus\Meditation\Primitives\CompoundTypes;
use Chromabits\Nucleus\Meditation\Spec;
use Chromabits\Nucleus\Support\Arr;

/**
 * Class SpecSchemaEncoder.
 *
 * Generates JSON schemas from Nucleus specs.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Json
 */
class SpecSchemaEncoder extends BaseObject
{
    /**
     * Attempt to encode a Spec into a JSON schema.
     *
     * @param Spec $spec
     * @param string $title
     *
     * @return string
     * @throws CoreException
     */
    public function encode(Spec $spec, $title = 'root')
    {
        $schema = [
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'title' => $title,
            'type' => CompoundTypes::COMPOUND_OBJECT,
            'required' => $spec->getRequired(),
        ];
        $properties = [];

        foreach ($spec->getConstraints() as $key => $constraints) {
            $properties[$key] = $this->encodeConstraints(
                $spec,
                $constraints,
                $key
            );
        }

        foreach ($spec->getDefaults() as $key => $default) {
            if (!Arr::has($properties, $key)) {
                $properties[$key] = [];
            }

            $properties[$key]['default'] = $default;
        }

        $schema['properties'] = $properties;

        return json_encode($schema, JSON_PRETTY_PRINT);
    }

    /**
     * Encode constraints into JSON schema types/constraints.
     *
     * @param Spec $spec
     * @param AbstractConstraint|AbstractConstraint[]|Spec $input
     * @param string $title
     *
     * @return array
     * @throws CoreException
     */
    public function encodeConstraints(Spec $spec, $input, $title)
    {
        if ($input instanceof AbstractConstraint) {
            if ($input instanceof PrimitiveTypeConstraint) {
                return [
                    'type' => $input->toString(),
                ];
            }

            return [];
        } elseif (is_array($input)) {
            foreach ($input as $constraint) {
                if ($constraint instanceof PrimitiveTypeConstraint) {
                    return [
                        'type' => $input->toString(),
                    ];
                }
            }

            return [];
        } elseif ($input instanceof Spec) {
            return (new static())->encode($input, $title);
        }

        throw new CoreException('Unexpected constraint type.');
    }
}