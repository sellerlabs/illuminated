<?php

namespace Tests\Chromabits\Illuminated\Database\Migrations;

use Chromabits\Illuminated\Database\Migrations\StatusReport;
use Chromabits\Nucleus\Testing\TestCase;

/**
 * Class StatusReportTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Database\Migrations
 */
class StatusReportTest extends TestCase
{
    public function testAll()
    {
        $migrations = [
            'doge1',
            'doge2',
            'doge3',
            'omg_laradoge',
            '111!!111one11111eleven!!',
        ];

        $ran = [
            'doge1',
            'doge2',
            'doge3',
            'coffee',
            'posts',
        ];

        $report = new StatusReport($migrations, $ran);

        $this->assertEquals($migrations, $report->getMigrations());
        $this->assertEquals($ran, $report->getRan());
        $this->assertEquals(
            [
                'omg_laradoge',
                '111!!111one11111eleven!!',
            ],
            $report->getIdle()
        );
        $this->assertEquals(
            [
                'coffee',
                'posts',
            ],
            $report->getUnknown()
        );
    }
}
