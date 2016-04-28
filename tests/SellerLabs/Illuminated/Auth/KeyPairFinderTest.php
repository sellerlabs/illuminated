<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Auth;

use SellerLabs\Illuminated\Auth\Models\KeyPair;
use Tests\SellerLabs\Illuminated\Auth\AuthDatabaseTrait;
use Tests\SellerLabs\Support\IlluminatedTestCase;

/**
 * Class KeyPairFinderTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Auth
 */
class KeyPairFinderTest extends IlluminatedTestCase
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

    public function testFindByPublicId()
    {
        $pair = new KeyPair();
        $pair2 = new KeyPair();
        $pair3 = new KeyPair();

        $finder = new KeyPairFinder();

        $pair->public_id = 'omg';
        $pair->secret_key = 'wow';
        $pair->type = KeyPairTypes::TYPE_GENERIC;
        $pair->save();

        $pair2->public_id = 'omg';
        $pair2->secret_key = 'dolan';
        $pair2->type = KeyPairTypes::TYPE_HMAC;
        $pair2->save();

        $pair3->public_id = 'omgs';
        $pair3->secret_key = 'doges';
        $pair3->type = KeyPairTypes::TYPE_HMAC;
        $pair3->save();

        $fetchSecret = function ($id, $type) use ($finder) {
            return $finder->byPublicId($id, $type)->secret_key;
        };

        $this->assertEqualsMatrix([
            ['wow', $fetchSecret('omg', KeyPairTypes::TYPE_GENERIC)],
            ['dolan', $fetchSecret('omg', KeyPairTypes::TYPE_HMAC)],
            ['doges', $fetchSecret('omgs', KeyPairTypes::TYPE_HMAC)],
        ]);
    }
}
