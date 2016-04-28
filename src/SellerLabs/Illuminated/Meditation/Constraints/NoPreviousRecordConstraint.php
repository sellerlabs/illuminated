<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Meditation\Constraints;

use Illuminate\Database\Query\Builder;
use SellerLabs\Nucleus\Meditation\Constraints\AbstractConstraint;

/**
 * Class NoPreviousRecordConstraint.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Meditation\Constraints
 */
class NoPreviousRecordConstraint extends AbstractConstraint
{
    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var array
     */
    protected $where;

    /**
     * Construct an instance of a ExactlyOneRecordConstraint.
     *
     * @param Builder $query
     * @param array $where
     */
    public function __construct(Builder $query, array $where = [])
    {
        parent::__construct();

        $this->query = $query;
        $this->where = $where;
    }

    /**
     * Check if the constraint is met.
     *
     * @param mixed $value
     * @param array $context
     *
     * @return mixed
     */
    public function check($value, array $context = [])
    {
        if (count($this->where) > 0) {
            $query = clone $this->query;

            foreach ($this->where as $field => $value) {
                $query->where($field, '=', $context[$value]);
            }

            return $query->count() === 0;
        }

        return $this->query->count() === 0;
    }

    /**
     * Get string representation of this constraint.
     *
     * @return mixed
     */
    public function toString()
    {
        return '{$query->count() === 0}';
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return 'The value is expected to constraint a query to exactly 0'
            . ' records.';
    }
}
