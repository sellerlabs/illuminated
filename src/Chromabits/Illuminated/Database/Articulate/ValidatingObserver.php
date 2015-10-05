<?php

namespace Chromabits\Illuminated\Database\Articulate;

use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Meditation\Exceptions\FailedCheckException;

/**
 * Class ValidatingObserver
 *
 * Originally from: https://github.com/AltThree/Validator/
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Articulate
 */
class ValidatingObserver extends BaseObject
{
    /**
     * Validate the model on saving.
     *
     * @param Model $model
     */
    public function saving(Model $model)
    {
        $this->validate($model);
    }

    /**
     * Validate the model on saving.
     *
     * @param Model $model
     */
    public function restoring(Model $model)
    {
        $this->validate($model);
    }

    protected function validate(Model $model)
    {
        $attributes = $model->getAttributes();
        $checkable = $model->getCheckable();

        if ($checkable === null) {
            return;
        }

        $result = $checkable->check($attributes);

        if ($result->failed()) {
            new FailedCheckException($checkable, $result);
        }
    }
}