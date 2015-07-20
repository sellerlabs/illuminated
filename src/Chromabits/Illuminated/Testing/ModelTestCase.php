<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Testing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class ModelTestCase
 *
 * A test case with utilities for asserting the behavior of models.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Testing
 */
abstract class ModelTestCase extends LaravelTestCase
{
    const HAS_ONE = 'hasOne';
    const HAS_MANY = 'hasMany';
    const BELONGS_TO = 'belongsTo';
    const BELONGS_TO_MANY = 'belongsToMany';

    /**
     * Assert the the model defines a HasOne relationship.
     *
     * @param $model
     * @param $property
     * @param $other
     */
    protected function assertHasOne($model, $property, $other)
    {
        $this->assertTrue(method_exists($model, $property));

        /** @var HasOne $relation */
        $relation = $model->$property();

        $this->assertInstanceOf(HasOne::class, $relation);
        $this->assertInstanceOf($other, $relation->getRelated());
    }

    /**
     * Assert the the model defines a HasMany relationship.
     *
     * @param $model
     * @param $property
     * @param $other
     */
    protected function assertHasMany($model, $property, $other)
    {
        $this->assertTrue(method_exists($model, $property));

        /** @var HasOne $relation */
        $relation = $model->$property();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf($other, $relation->getRelated());
    }

    /**
     * Assert the the model defines a BelongsTo relationship.
     *
     * @param $model
     * @param $property
     * @param $other
     */
    protected function assertBelongsTo($model, $property, $other)
    {
        $this->assertTrue(method_exists($model, $property));

        /** @var HasOne $relation */
        $relation = $model->$property();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf($other, $relation->getRelated());
    }

    /**
     * Assert the the model defines a BelongsToMany relationship.
     *
     * @param $model
     * @param $property
     * @param $other
     */
    protected function assertBelongsToMany($model, $property, $other)
    {
        $this->assertTrue(method_exists($model, $property));

        /** @var HasOne $relation */
        $relation = $model->$property();

        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertInstanceOf($other, $relation->getRelated());
    }

    /**
     * Return a list of relations to be tested.
     *
     * @return mixed
     */
    abstract public function relationsProvider();

    /**
     * Make an instance of the model being tested.
     *
     * @return Model
     */
    abstract protected function make();

    /**
     * Tests relationship definitions.
     *
     * @param string $property
     * @param string $type
     * @param string $other
     *
     * @dataProvider relationsProvider
     */
    public function testRelations($property, $type, $other)
    {
        $model = $this->make();

        switch ($type) {
            case static::HAS_ONE:
                $this->assertHasOne($model, $property, $other);
                break;
            case static::HAS_MANY:
                $this->assertHasMany($model, $property, $other);
                break;
            case static::BELONGS_TO:
                $this->assertBelongsTo($model, $property, $other);
                break;
            case static::BELONGS_TO_MANY:
                $this->assertBelongsToMany($model, $property, $other);
                break;
        }
    }
}
