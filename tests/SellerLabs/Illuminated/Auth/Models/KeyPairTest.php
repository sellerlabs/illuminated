<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Auth\Models;

use Tests\SellerLabs\Illuminated\Auth\AuthDatabaseTrait;
use Tests\SellerLabs\Support\IlluminatedTestCase;

/**
 * Class KeyPairTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Auth\Models
 */
class KeyPairTest extends IlluminatedTestCase
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

        $this->assertEquals([
            'name' => 'dolan',
        ], $pair2->data);
    }
}
