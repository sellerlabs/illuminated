<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Hashing;

use SellerLabs\Illuminated\Hashing\Md5Hasher;
use SellerLabs\Nucleus\Testing\TestCase;

/**
 * Class Md5HasherTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Hashing
 */
class Md5HasherTest extends TestCase
{
    public function testCheck()
    {
        $hasher = new Md5Hasher();

        $hash = md5('My crappy password hash');

        $this->assertEqualsMatrix([
            [true, $hasher->check('My crappy password hash', $hash)],
            [false, $hasher->check('My crappy password', $hash)],
        ]);
    }

    public function testMake()
    {
        $hasher = new Md5Hasher();

        $one = $hasher->make('wow this is so secure, right?');
        $two = $hasher->make('this will keep the h4x0r away');

        $this->assertNotEquals($one, $two);

        $this->assertEqualsMatrix([
            [true, $hasher->check('wow this is so secure, right?', $one)],
            [true, $hasher->check('this will keep the h4x0r away', $two)],
        ]);
    }
}
