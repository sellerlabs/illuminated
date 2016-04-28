<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Database\Articulate;

use Tests\SellerLabs\Support\IlluminatedTestCase;

/**
 * Class TableTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Database\Articulate
 */
class TableTest extends IlluminatedTestCase
{
    public function testFits()
    {
        $this->assertTrue(Table::fits('wow', Table::TYPE_VARCHAR));
        $this->assertTrue(
            Table::fits($this->getLongString(25600), Table::TYPE_LONGTEXT)
        );

        $this->assertFalse(Table::fits('wow', Table::TYPE_VARCHAR, 2));
        $this->assertFalse(
            Table::fits($this->getLongString(25600), Table::TYPE_TEXT)
        );
    }

    /**
     * Get a really long string.
     *
     * @param int $iterations
     *
     * @return string
     */
    protected function getLongString($iterations = 32)
    {
        $base = 'doges-gonna-wow';
        $longString = '';

        for ($ii = 0; $ii < $iterations; $ii++) {
            $longString .= $base;
        }

        return $longString;
    }
}
