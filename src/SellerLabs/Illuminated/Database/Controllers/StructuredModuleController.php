<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Database\Controllers;

use SellerLabs\Illuminated\Database\Interfaces\StructuredStatusInterface;
use SellerLabs\Illuminated\Database\Views\ConferenceStructuredStatisticsPresenter;
use SellerLabs\Illuminated\Http\BaseController;
use SellerLabs\Nucleus\Support\Std;
use SellerLabs\Nucleus\View\Bootstrap\Card;
use SellerLabs\Nucleus\View\Bootstrap\CardHeader;
use SellerLabs\Nucleus\View\Bootstrap\SimpleTable;
use SellerLabs\Nucleus\View\Common\Div;
use SellerLabs\Nucleus\View\Common\Italic;

/**
 * Class StructuredModuleController.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Database\Controllers
 */
class StructuredModuleController extends BaseController
{
    /**
     * Get pending and exotic migrations.
     *
     * @param StructuredStatusInterface $status
     *
     * @return Div
     */
    public function getIndex(StructuredStatusInterface $status)
    {
        $report = $status->generateReport();

        return new Div(
            [], [
                new ConferenceStructuredStatisticsPresenter($report),
                new Card(
                    [], [
                        new CardHeader([], 'Pending Migrations'),
                        Std::firstBias(
                            count($report->getIdle()) > 0,
                            function () use ($report) {
                                return new SimpleTable(
                                    ['-', 'Name'],
                                    Std::map(
                                        function ($name) {
                                            return [
                                                new Italic(
                                                    [
                                                        'class' => [
                                                            'fa',
                                                            'fa-circle-o',
                                                            'text-warning',
                                                        ],
                                                    ]
                                                ),
                                                $name,
                                            ];
                                        },
                                        $report->getIdle()
                                    )
                                );
                            },
                            function () {
                                return new Div(
                                    ['class' => 'card-block text-center'], [
                                        'There are no pending migrations.',
                                    ]
                                );
                            }
                        ),
                    ]
                ),
                new Card(
                    [], [
                        new CardHeader([], 'Exotic Migrations'),
                        Std::firstBias(
                            count($report->getUnknown()) > 0,
                            function () use ($report) {
                                return new SimpleTable(
                                    ['-', 'Name'],
                                    Std::map(
                                        function ($name) {
                                            return [
                                                new Italic(
                                                    [
                                                        'class' => [
                                                            'fa',
                                                            'fa-times-circle',
                                                            'text-danger',
                                                        ],
                                                    ]
                                                ),
                                                $name,
                                            ];
                                        },
                                        $report->getUnknown()
                                    )
                                );
                            },
                            function () {
                                return new Div(
                                    ['class' => 'card-block text-center'], [
                                        'There are no exotic migrations.',
                                    ]
                                );
                            }
                        ),
                    ]
                ),
            ]
        );
    }

    /**
     * Get all migrations.
     *
     * @param StructuredStatusInterface $status
     *
     * @return Card
     */
    public function getAll(StructuredStatusInterface $status)
    {
        $report = $status->generateReport();

        $idle = $report->getIdle();
        $unknown = $report->getUnknown();
        $ran = $report->getRan();

        return new Card(
            [], [
                new CardHeader([], 'All Migrations'),
                new SimpleTable(
                    ['-', 'Status', 'Name'],
                    Std::map(
                        function ($name) use ($idle, $unknown, $ran) {
                            if (in_array($name, $idle)) {
                                return [
                                    new Italic(
                                        [
                                            'class' => [
                                                'fa',
                                                'fa-circle-o',
                                                'text-warning',
                                            ],
                                        ]
                                    ),
                                    'Pending',
                                    $name,
                                ];
                            } elseif (in_array($name, $unknown)) {
                                return [
                                    new Italic(
                                        [
                                            'class' => [
                                                'fa',
                                                'fa-times-circle',
                                                'text-danger',
                                            ],
                                        ]
                                    ),
                                    'Exotic',
                                    $name,
                                ];
                            } elseif (in_array($name, $ran)) {
                                return [
                                    new Italic(
                                        [
                                            'class' => [
                                                'fa',
                                                'fa-check-circle',
                                                'text-success',
                                            ],
                                        ]
                                    ),
                                    'Ran',
                                    $name,
                                ];
                            }

                            return [
                                new Italic(
                                    [
                                        'class' => [
                                            'fa',
                                            'fa-circle-o',
                                        ],
                                    ]
                                ),
                                '???',
                                $name,
                            ];
                        },
                        Std::concat(
                            $report->getMigrations(),
                            $report->getUnknown()
                        )
                    )
                ),
            ]
        );
    }
}
