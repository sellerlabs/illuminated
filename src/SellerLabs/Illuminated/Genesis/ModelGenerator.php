<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Genesis;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use SellerLabs\Illuminated\Database\Articulate\Model;
use SellerLabs\Illuminated\Meditation\ModelInspector;
use SellerLabs\Nucleus\Exceptions\LackOfCoffeeException;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Meditation\Arguments;
use SellerLabs\Nucleus\Meditation\Boa;
use SellerLabs\Nucleus\Meditation\Exceptions\InvalidArgumentException;
use SellerLabs\Nucleus\Meditation\TypeHound;
use SellerLabs\Nucleus\Support\Arr;
use SellerLabs\Nucleus\Support\Std;
use SellerLabs\Nucleus\Transformation\TransformPipeline;

/**
 * Class ModelGenerator.
 *
 * The model generator attempts to populate models and their relationships.
 *
 * A developer will need to extend this class for each model they need
 * generated.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Genesis
 */
abstract class ModelGenerator extends BaseObject
{
    /**
     * Name of the model class this generator will be creating.
     *
     * @var string|null
     */
    protected $model = null;

    /**
     * User-provided overrides for some fields.
     *
     * @var array
     */
    protected $overrides = [];

    /**
     * Relationships to be generated.
     *
     * @var array
     */
    protected $relations = [];

    /**
     * The generator to use for a specific relationship.
     *
     * @var ModelGenerator[]
     */
    protected $relationsGenerators = [];

    /**
     * The number of elements to generate for a specific relationship.
     *
     * @var array
     */
    protected $relationsCount = [];

    /**
     * Relationships that only require one other model.
     *
     * @var array
     */
    protected $singleRelations
        = [
            BelongsTo::class,
            HasOne::class,
        ];

    /**
     * Relationships that use multiple models.
     *
     * @var array
     */
    protected $multipleRelations
        = [
            BelongsToMany::class,
            HasMany::class,
            HasManyThrough::class,
        ];

    /**
     * Internal model inspector.
     *
     * @var ModelInspector|null
     */
    protected $inspector = null;

    /**
     * Internal instance of the model being generated.
     *
     * @var Model|null
     */
    protected $modelInstance = null;

    /**
     * Override a specific generated property with the provided value.
     *
     * The provided value may be a fixed value or a Closure. If a Closure is
     * provided, it will be called when the model is generated.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function let($key, $value)
    {
        $this->overrides[$key] = $value;

        return $this;
    }

    /**
     * Provide the generator a specific relationship.
     *
     * An optional count may also be included if a relationship involves many
     * models. The count only affects the related model, not the model
     * generated by this generator.
     *
     * @param string $relationName
     * @param ModelGenerator $generator
     * @param int $count
     *
     * @throws LackOfCoffeeException
     * @return $this
     */
    public function with($relationName, ModelGenerator $generator, $count = 1)
    {
        $inspector = $this->getInspector($this->getModelInstance());

        $relation = $inspector->getRelation($relationName);
        $relationModelClass = get_class($relation->getRelated());

        if ($relationModelClass !== $generator->getModel()) {
            throw new LackOfCoffeeException(vsprintf(
                'The provided generator for the relationship "%s" is' .
                ' capable of generating %s, but the relationship uses' .
                '%s.',
                [$relationName, $generator->getModel(), $relationModelClass]
            ));
        }

        $this->relations[$relationName] = $relation;
        $this->relationsGenerators[$relationName] = $generator;

        if ($count > 1
            && in_array(get_class($generator), $this->multipleRelations)
        ) {
            $this->relationsCount[$relationName] = $count;
        }

        return $this;
    }

    /**
     * Set a relationship of the model explicitly.
     *
     * @param string $relationName
     * @param Model|Model[] $related
     *
     * @throws LackOfCoffeeException
     * @throws InvalidArgumentException
     * @return $this
     */
    public function withFixed($relationName, $related)
    {
        $inspector = $this->getInspector($this->getModelInstance());

        $relation = $inspector->getRelation($relationName);
        $relationModelClass = get_class($relation->getRelated());

        Arguments::define(
            Boa::string(),
            Boa::either(
                Boa::instance($relationModelClass),
                Boa::arrOf(Boa::instance($relationModelClass))
            )
        )->check($relationName, $related);

        $this->relations[$relationName] = $relation;
        $this->relationsGenerators[$relationName] = $related;

        return $this;
    }

    /**
     * Get an instance of an inspector for the model being generated.
     *
     * The inspector is used to get useful information on the model, which can
     * be used to automate generation.
     *
     * Note: The instance will be cached after being created for the first
     * time.
     *
     * @param Model $model
     *
     * @return ModelInspector|null
     */
    protected function getInspector(Model $model)
    {
        if ($this->inspector === null) {
            $this->inspector = new ModelInspector($model);
        }

        return $this->inspector;
    }

    /**
     * Get an instance of the model being generated.
     *
     * Note: The instance will be cached after being created for the first
     * time.
     *
     * @param bool $flush Remove old instance
     *
     * @throws LackOfCoffeeException
     * @return Model
     */
    protected function getModelInstance($flush = false)
    {
        if ($this->modelInstance !== null && $flush === false) {
            return $this->modelInstance;
        }

        $model = new $this->model();

        if (!$model instanceof Model) {
            throw new LackOfCoffeeException(vsprintf(
                'ModelGenerator only supports generating instances of' .
                ' %s. Got %s',
                [Model::class, TypeHound::fetch($model)]
            ));
        }

        $this->modelInstance = $model;

        return $model;
    }

    /**
     * Get the class name of the model being generated.
     *
     * @return string|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Generate the model.
     *
     * @throws LackOfCoffeeException
     * @return Model
     */
    public function make()
    {
        // Make model instance
        $model = $this->getModelInstance(true);

        $filling = TransformPipeline::define()
            ->inline(function ($input) {
                return array_merge($input, $this->overrides);
            })
            ->inline(function ($input) {
                return Std::map(function ($value) {
                    return Std::thunk($value);
                }, $input);
            })
            ->run($this->getMapping());

        Std::each(function ($value, $key) use (&$model) {
            $model->$key = $value;
        }, $filling);

        Std::each(function ($relation, $name) use (&$model) {
            if (!$this->isBelongsTo($name)) {
                return;
            }

            $model->$name()->associate(
                $this->generateRelation($name)
            );
        }, $this->relations);

        $model->save();

        Std::each(function ($relation, $name) use (&$model) {
            if ($this->isBelongsTo($name)) {
                return;
            }

            $related = $this->generateRelation($name);

            if (is_array($related)) {
                $model->$name()->saveMany($related);
            } else {
                $model->$name()->save($related);
            }
        }, $this->relations);

        return $model;
    }

    /**
     * Generate multiple models.
     *
     * @param int $count
     *
     * @return Model[]
     */
    public function makeMany($count)
    {
        $models = [];

        for ($ii = 0; $ii < $count; $ii++) {
            $models[] = $this->make();
        }

        return $models;
    }

    /**
     * Return whether or not a relationship is a belongs to relation.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function isBelongsTo($name)
    {
        return ($this->inspector->getRelation($name) instanceof BelongsTo);
    }

    /**
     * Build out the models for a relation.
     *
     * @param string $name
     *
     * @return Model|Collection
     */
    protected function generateRelation($name)
    {
        $count = Arr::dotGet($this->relationsCount, $name, 1);

        if (!$this->relationsGenerators[$name] instanceof ModelGenerator) {
            return $this->relationsGenerators[$name];
        }

        if ($count === 1) {
            return $this->relationsGenerators[$name]->make();
        }

        return $this->relationsGenerators[$name]->makeMany($count);
    }

    /**
     * Generate a mapping of fields and their values.
     *
     * This is the main method that a subclass should implement. It provides a
     * mapping of each field name in the model with a fixed value to be used or
     * a closure or Closure capable of producing said value:
     *
     *  'username' => 'mr-doge-95',
     *  'age' => function () { return rand(18, 27); },
     *
     * If a Closure is used, it will be called when the model is generated.
     * This property is useful when multiple models are generated from the same
     * generator, since the will all contain different values.
     *
     * Note: One may skip some value or include additional ones that are not
     * on the models database table. However, this might prevent the model from
     * being capable of being saved into a database row.
     *
     * @return mixed
     */
    abstract protected function getMapping();
}
