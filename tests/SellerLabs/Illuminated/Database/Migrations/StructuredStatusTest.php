<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Database\Migrations;

use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Mockery\MockInterface;
use SellerLabs\Illuminated\Database\Migrations\Batch;
use SellerLabs\Illuminated\Database\Migrations\StatusReport;
use SellerLabs\Illuminated\Database\Migrations\StructuredStatus;
use SellerLabs\Nucleus\Testing\Impersonator;
use SellerLabs\Nucleus\Testing\TestCase;

/**
 * Class StructuredStatusTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Database\Migrations
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
                    ->andReturn(
                        [
                            'omgdoge',
                            'goobypls',
                        ]
                    );

                $mock->shouldReceive('repositoryExists')->once()
                    ->andReturn(true);
            }
        );

        $imp->mock(
            Batch::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('getMigrations')
                    ->once()
                    ->andReturn(
                        [
                            'omgdoge',
                        ]
                    );
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
                    ->andReturn(
                        [
                            'omgdoge',
                            'goobypls',
                        ]
                    );

                $mock->shouldReceive('resolve')->with('omgdoge')->once()
                    ->andReturn('wowwow');
                $mock->shouldReceive('resolve')->with('goobypls')->once()
                    ->andReturn('dolanpls');
            }
        );

        /** @var StructuredStatus $status */
        $status = $imp->make(StructuredStatus::class);

        $result = $status->getResolvedMap();
        $this->assertEquals(
            [
                'omgdoge' => 'wowwow',
                'goobypls' => 'dolanpls',
            ],
            $result
        );
    }
}
