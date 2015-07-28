<?php

namespace Chromabits\Illuminated\Database\Articulate;

use Illuminate\Database\Eloquent\Model;

/**
 * Class JsonModel
 *
 * An extension of Eloquent capable of saving some fields as JSON arrays in the
 * database.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Articulate
 */
class JsonModel extends Model
{
    /**
     * Fields that should be handled as JSON in the database.
     *
     * @var array
     */
    protected $json = [];

    /**
     * Register model events.
     */
    public static function registerEvents()
    {
        // Define a handler for converting the specified properties into JSON
        // before saving the model to the database
        static::saving(
            function (static $model) {
                foreach ($model->getJsonFields() as $field) {
                    $model->$field = json_encode($model->$field);
                }
            }
        );

        // Restore the specified properties back into arrays after saving
        static::saved(
            function (static $model) {
                foreach ($model->getJsonFields() as $field) {
                    $model->$field = json_decode($model->$field, true);
                }
            }
        );
    }

    /**
     * @inheritdoc
     */
    public function newFromBuilder($attributes = [])
    {
        $model = parent::newFromBuilder($attributes);

        foreach ($this->getJsonFields() as $field) {
            $model->$field = json_decode($model->$field, true);
        }

        return $model;
    }

    /**
     * Get which fields should be handled as JSON in the database.
     *
     * @return array
     */
    public function getJsonFields()
    {
        return $this->json;
    }
}
