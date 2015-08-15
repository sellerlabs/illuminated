<?php

namespace Tests\Chromabits\Illuminated\Hashing;

use Chromabits\Illuminated\Hashing\AggregatedHasher;
use Chromabits\Nucleus\Testing\TestCase;

/**
 * Class AggregatedHasherTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Hashing
 */
class AggregatedHasherTest extends TestCase
{
    public function testMake()
    {
        $hasher = new AggregatedHasher();

        $one = $hasher->make('wow this is so secure, right?');
        $two = $hasher->make('this will keep the h4x0r away');

        $this->assertNotEquals($one, $two);

        $this->assertEqualsMatrix([
            [true, $hasher->check('wow this is so secure, right?', $one)],
            [true, $hasher->check('this will keep the h4x0r away', $two)],
        ]);
    }

    public function testCheck()
    {
        $hasher = new AggregatedHasher();

        $one = 'plain text is cool';
        $two = md5('plain text is cool');
        $three = sha1('plain text is cool');
        $four = $hasher->make('plain text is cool', ['rounds' => 4]);
        $five = $hasher->make('plain text is cool');

        $this->assertEqualsMatrix([
            [false, $hasher->check('plain text is cool', $one)],
            [true, $hasher->check('plain text is cool', $two)],
            [false, $hasher->check('plain text is cool', $three)],
            [true, $hasher->check('plain text is cool', $four)],
            [true, $hasher->check('plain text is cool', $five)],
        ]);
    }

    public function testNeedsRehash()
    {
        $hasher = new AggregatedHasher();

        $this->assertEqualsMatrix([
            [true, $hasher->needsRehash('plaintext is the best')],
            [true, $hasher->needsRehash(md5('anything'))],
            [true, $hasher->needsRehash(sha1('anything'))],
            [true, $hasher->needsRehash($hasher->make('hi', ['rounds' => 5]))],
            [false, $hasher->needsRehash($hasher->make('hi'))]
        ]);
    }
}
