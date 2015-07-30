<?php

namespace Chromabits\Illuminated\Auth\Models;

use Chromabits\Illuminated\Database\Articulate\JsonModel;

/**
 * Class KeyPair
 *
 * @property int id
 * @property string public_id
 * @property string secret_key
 * @property string type
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth\Models
 */
class KeyPair extends JsonModel
{
    protected $table = 'illuminated_key_pairs';

    /**
     * Fields that should be handled as JSON in the database.
     *
     * @var string[]
     */
    protected $json = ['data'];
}
