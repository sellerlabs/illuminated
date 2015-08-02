<?php

namespace Chromabits\Illuminated\Meditation\Constraints;

use Chromabits\Nucleus\Meditation\Constraints\AbstractConstraint;
use Illuminate\Database\Query\Builder;

/**
 * Class ExactlyOneRecordConstraint
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Meditation\Constraints
 */
class ExactlyOneRecordConstraint extends AbstractConstraint
{
    protected $query;

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
                $query->where($field, '=', $field);
            }

            return $query->count() === 1;
        }

        return $this->query->count() === 1;
    }

    /**
     * Get string representation of this constraint.
     *
     * @return mixed
     */
    public function toString()
    {
        return '0 < $query->count() < 2';
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return 'The value is expected to constraint a query result to exactly'
            . ' a single record (queryResult == 1). If the query result is 0'
            . ' or greater than 1, then the value is considered to be unable'
            . ' to meet this constraint.';
    }
}
