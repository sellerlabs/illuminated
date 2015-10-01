<?php

namespace Chromabits\Illuminated\Database\Interfaces;

use Chromabits\Illuminated\Meditation\Constraints\ExactlyOneRecordConstraint;
use Chromabits\Nucleus\Exceptions\LackOfCoffeeException;
use Chromabits\Nucleus\Meditation\Arguments;
use Chromabits\Nucleus\Meditation\Boa;
use Chromabits\Nucleus\Meditation\Exceptions\InvalidArgumentException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface BaseRepositoryInterface
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Interfaces
 */
interface BaseRepositoryInterface
{
    /**
     * Get a spec constraint for ensuring that the model exists.
     *
     * The field name defaults to `id`.
     *
     * @param string $idField
     *
     * @return ExactlyOneRecordConstraint
     * @throws LackOfCoffeeException
     * @throws InvalidArgumentException
     */
    public function makeExistsConstraint($idField = 'id');

    /**
     * Get a model by its id.
     *
     * @param integer $id
     * @param array $columns
     * @param array $with
     *
     * @return Model
     * @throws InvalidArgumentException
     * @throws LackOfCoffeeException
     */
    public function getById($id, array $columns = ['*'], array $with = []);

    /**
     * Perform a simple where query and return a collection of the matching
     * models.
     *
     * @param array $fieldConditions
     * @param array $columns
     * @param array $with
     *
     * @return Collection
     */
    public function where(
        array $fieldConditions,
        array $columns = ['*'],
        array $with = []
    );

    /**
     * Perform a simple where query and return a paginator of the matching
     * models.
     *
     * @param array $fieldConditions
     * @param array $columns
     * @param array $with
     * @param int $take
     * @param string $pageName
     * @param null|integer $page
     *
     * @return LengthAwarePaginator
     */
    public function wherePaginated(
        array $fieldConditions,
        array $columns = ['*'],
        array $with = [],
        $take = 25,
        $pageName = 'page',
        $page = null
    );

    /**
     * Get all models.
     *
     * This is a fairly dumb function unless you have a really small table.
     * You may run of memory or query might never finish. Use with care.
     *
     * @param array $columns
     * @param array $with
     *
     * @return Collection
     * @throws LackOfCoffeeException
     */
    public function getAll($columns = ['*'], $with = []);

    /**
     * Get all models paginated.
     *
     * @param array $columns
     * @param array $with
     * @param int $take
     * @param string $pageName
     * @param null|integer $page
     *
     * @return LengthAwarePaginator
     * @throws LackOfCoffeeException
     */
    public function getAllPaginated(
        $columns = ['*'],
        $with = [],
        $take = 25,
        $pageName = 'page',
        $page = null
    );

    /**
     * Find a model by its id and then update its contents.
     *
     * @param integer $id
     * @param array $fill
     *
     * @return Model
     */
    public function updateById($id, array $fill);

    /**
     * Perform a simple where query and return a single element matching the
     * query, or fail.
     *
     * @param array $fieldConditions
     * @param array $columns
     * @param array $with
     *
     * @return Collection
     */
    public function whereFirstOrFail(
        array $fieldConditions,
        array $columns = ['*'],
        array $with = []
    );

    /**
     * Check whether or not the model with the specified ID exists.
     *
     * @param integer $id
     *
     * @return bool
     * @throws InvalidArgumentException
     * @throws LackOfCoffeeException
     */
    public function exists($id);
}