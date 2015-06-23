<?php

namespace Tests\Chromabits\Illuminated\Database\Migrations;

use Chromabits\Illuminated\Database\Migrations\Batch;
use Chromabits\Illuminated\Database\Migrations\StatusReport;
use Chromabits\Illuminated\Database\Migrations\StructuredStatus;
use Chromabits\Nucleus\Testing\Impersonator;
use Chromabits\Nucleus\Testing\TestCase;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Mockery\MockInterface;

/**
 * Class StructuredStatusTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Database\Migrations
 */
class StructuredStatusTest extends TestCase
{
    public function testGenerateReport()
    {
        $imp = new Impersonator();

        $imp->mock(
            MigrationRepositoryInterface::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('getRan')->once()
                    ->andReturn([
                        'omgdoge',
                        'goobypls',
                    ]);

                $mock->shouldReceive('repositoryExists')->once()
                    ->andReturn(true);
            }
        );

        $imp->mock(
            Batch::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('getMigrations')
                    ->once()
                    ->andReturn([
                        'omgdoge',
                    ]);
            }
        );

        /** @var StructuredStatus $status */
        $status = $imp->make(StructuredStatus::class);

        $result = $status->generateReport();

        $this->assertInstanceOf(StatusReport::class, $result);
        $this->assertEquals(['omgdoge'], $result->getMigrations());
        $this->assertEquals(['omgdoge', 'goobypls'], $result->getRan());
    }

    public function testGetResolvedMap()
    {
        $imp = new Impersonator();

        $imp->mock(
            Batch::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('getMigrations')
                    ->once()
                    ->andReturn([
                        'omgdoge',
                        'goobypls',
                    ]);

                $mock->shouldReceive('resolve')->with('omgdoge')->once()
                    ->andReturn('wowwow');
                $mock->shouldReceive('resolve')->with('goobypls')->once()
                    ->andReturn('dolanpls');
            }
        );

        /** @var StructuredStatus $status */
        $status = $imp->make(StructuredStatus::class);

        $result = $status->getResolvedMap();
        $this->assertEquals([
            'omgdoge' => 'wowwow',
            'goobypls' => 'dolanpls',
        ], $result);
    }
}
