<?php

namespace Chromabits\Illuminated\Auth;

use Chromabits\Illuminated\Auth\Models\KeyPair;
use Tests\Chromabits\Illuminated\Auth\AuthDatabaseTrait;
use Tests\Chromabits\Support\HelpersTestCase;

/**
 * Class KeyPairGeneratorTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Auth
 */
class KeyPairGeneratorTest extends HelpersTestCase
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

    public function testGenerateHmac()
    {
        $generator = new KeyPairGenerator();
        $key = $generator->generateHmac();

        $this->assertEqualsMatrix([
            [128, strlen($key->getPublicId())],
            [128, strlen($key->getSecretKey())],
            [KeyPairTypes::TYPE_HMAC, $key->getType()],
            [[], $key->getData()],
        ]);

        $generator2 = new KeyPairGenerator();
        $key2 = $generator2->generateHmac(256, 512, 'md5');

        $this->assertEqualsMatrix([
            [32, strlen($key2->getPublicId())],
            [32, strlen($key2->getSecretKey())],
            [KeyPairTypes::TYPE_HMAC, $key2->getType()],
            [[], $key2->getData()],
        ]);
    }
}