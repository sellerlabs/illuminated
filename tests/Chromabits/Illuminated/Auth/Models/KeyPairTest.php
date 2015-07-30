<?php

namespace Chromabits\Illuminated\Auth\Models;

use Tests\Chromabits\Illuminated\Auth\AuthDatabaseTrait;
use Tests\Chromabits\Support\HelpersTestCase;

/**
 * Class KeyPairTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth\Models
 */
class KeyPairTest extends HelpersTestCase
{
    use AuthDatabaseTrait;

    /**
     * Setup testing environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->migrateAuthDatabase();
        KeyPair::registerEvents();
    }

    public function testJsonProperties()
    {
        $pair = new KeyPair();

        $pair->public_id = 'wowowowow';
        $pair->secret_key = 'omgomgomgomgomg';
        $pair->type = 'testing';
        $pair->data = [
            'name' => 'dolan',
        ];

        $pair->save();

        $pair2 = KeyPair::query()
            ->where('public_id', 'wowowowow')
            ->firstOrFail();

        $this->assertEquals('omgomgomgomgomg', $pair2->secret_key);
    }
}
