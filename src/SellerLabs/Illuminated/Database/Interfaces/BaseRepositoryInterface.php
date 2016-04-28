<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Database\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use SellerLabs\Illuminated\Meditation\Constraints\ExactlyOneRecordConstraint;
use SellerLabs\Nucleus\Exceptions\LackOfCoffeeException;
use SellerLabs\Nucleus\Meditation\Exceptions\InvalidArgumentException;

/**
 * Interface BaseRepositoryInterface.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Database\Interfaces
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
     * @throws LackOfCoffeeException
     * @throws InvalidArgumentException
     * @return ExactlyOneRecordConstraint
     */
    public function makeExistsConstraint($idField = 'id');

    /**
     * Get a model by its id.
     *
     * @param int $id
     * @param array $columns
     * @param array $with
     *
     * @throws InvalidArgumentException
     * @throws LackOfCoffeeException
     * @return Model
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
     * @param null|int $page
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
     * @throws LackOfCoffeeException
     * @return Collection
     */
    public function getAll($columns = ['*'], $with = []);

    /**
     * Get all models paginated.
     *
     * @param array $columns
     * @param array $with
     * @param int $take
     * @param string $pageName
     * @param null|int $page
     *
     * @throws LackOfCoffeeException
     * @return LengthAwarePaginator
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
     * @param int $id
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
     * @param int $id
     *
     * @throws InvalidArgumentException
     * @throws LackOfCoffeeException
     * @return bool
     */
    public function exists($id);
}
