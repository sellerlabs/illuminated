<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Json;

use SellerLabs\Nucleus\Exceptions\CoreException;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Meditation\Constraints\AbstractConstraint;
use SellerLabs\Nucleus\Meditation\Constraints\PrimitiveTypeConstraint;
use SellerLabs\Nucleus\Meditation\Primitives\CompoundTypes;
use SellerLabs\Nucleus\Meditation\Spec;
use SellerLabs\Nucleus\Support\Arr;

/**
 * Class SpecSchemaEncoder.
 *
 * Generates JSON schemas from Nucleus specs.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Json
 */
class SpecSchemaEncoder extends BaseObject
{
    /**
     * Attempt to encode a Spec into a JSON schema.
     *
     * @param Spec $spec
     * @param string $title
     *
     * @throws CoreException
     * @return string
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
     * @throws CoreException
     * @return array
     */
    public function encodeConstraints(Spec $spec, $input, $title)
    {
        if ($input instanceof AbstractConstraint) {
            $schema = [
                'description' => $input->getDescription(),
            ];

            if ($input instanceof PrimitiveTypeConstraint) {
                $schema['type'] = $input->toString();
            }

            return $schema;
        } elseif (is_array($input)) {
            $schema = [];
            $descriptions = [];

            foreach ($input as $constraint) {
                $descriptions[] = $constraint->getDescription();

                if ($constraint instanceof PrimitiveTypeConstraint) {
                    $schema['type'] = $constraint->toString();
                }
            }

            $schema['description'] = implode('. ', $descriptions);

            return $schema;
        } elseif ($input instanceof Spec) {
            return (new static())->encode($input, $title);
        }

        throw new CoreException('Unexpected constraint type.');
    }
}
