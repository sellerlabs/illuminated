<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Database\Articulate;

use Tests\Chromabits\Support\HelpersTestCase;

/**
 * Class TableTest.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Articulate
 */
class TableTest extends HelpersTestCase
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
