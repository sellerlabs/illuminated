<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Conference\Controllers;

use Exception;
use SellerLabs\Illuminated\Conference\Entities\ConferenceContext;
use SellerLabs\Illuminated\Conference\Interfaces\DashboardInterface;
use SellerLabs\Illuminated\Conference\Method;
use SellerLabs\Illuminated\Conference\Module;
use SellerLabs\Illuminated\Http\BaseController;
use SellerLabs\Nucleus\Support\Std;
use SellerLabs\Nucleus\View\Bootstrap\Card;
use SellerLabs\Nucleus\View\Bootstrap\CardBlock;
use SellerLabs\Nucleus\View\Bootstrap\Column;
use SellerLabs\Nucleus\View\Bootstrap\Row;
use SellerLabs\Nucleus\View\Common\Anchor;
use SellerLabs\Nucleus\View\Common\Bold;
use SellerLabs\Nucleus\View\Common\Button;
use SellerLabs\Nucleus\View\Common\Div;
use SellerLabs\Nucleus\View\Common\HeaderFive;
use SellerLabs\Nucleus\View\Common\HeaderFour;
use SellerLabs\Nucleus\View\Common\HeaderSix;
use SellerLabs\Nucleus\View\Common\HorizontalLine;
use SellerLabs\Nucleus\View\Common\Italic;
use SellerLabs\Nucleus\View\Common\LineBreak;
use SellerLabs\Nucleus\View\Common\ListItem;
use SellerLabs\Nucleus\View\Common\Paragraph;
use SellerLabs\Nucleus\View\Common\PreformattedText;
use SellerLabs\Nucleus\View\Common\Small;
use SellerLabs\Nucleus\View\Common\UnorderedList;

/**
 * Class FrontModuleController.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Controllers
 */
class FrontModuleController extends BaseController
{
    /**
     * Get the homepage.
     *
     * @param DashboardInterface $dashboard
     * @param ConferenceContext $context
     *
     * @return Div
     */
    public function getIndex(
        DashboardInterface $dashboard,
        ConferenceContext $context
    ) {
        return (new Div([], [
            new Div(['class' => 'card'], [
                new Div(['class' => 'card-header'], 'About'),
                new Div(['class' => 'card-block'], [
                    new Bold([], [
                        'Hi there! This is the Illuminated Conference ',
                        'component.',
                    ]),
                    ' Here you will find a couple of built-in modules which ',
                    'you can use to debug and understand your application. ',
                    'However, you can also add your own modules for more ',
                    'specific tools!',
                ]),
            ]),
            new Div(['class' => 'row'], [
                new Div(['class' => 'col-sm-12'], [
                    new HeaderFive(['class' => 'p-b p-t'], 'Launchpad:'),
                ]),
            ]),
            $this->renderLaunchpad($dashboard, $context),
        ]));
    }

    /**
     * Render the launch bar.
     *
     * @param DashboardInterface $dashboard
     * @param ConferenceContext $context
     *
     * @return Div
     */
    protected function renderLaunchpad(
        DashboardInterface $dashboard,
        ConferenceContext $context
    ) {
        return new Div(['class' => 'card-columns'], [
            new Div(['class' => 'card-stack'], Std::map(
                function (Module $module) use ($context) {
                    return new Div(['class' => 'card'], [
                        new Div(['class' => 'card-block'], [
                            new Anchor(
                                [
                                    'href' => $context->module(
                                        $module->getName()
                                    ),
                                ],
                                [
                                    new HeaderFour(
                                        ['class' => 'card-title'],
                                        [
                                            $module->getLabel(),
                                            new Italic([
                                                'class' => 'pull-right fa ' .
                                                    $module->getIcon(),
                                            ]),
                                        ]
                                    ),
                                ]
                            ),
                            new Paragraph(
                                ['class' => 'card-text'],
                                $module->getDescription()
                            ),
                            new Paragraph(
                                ['class' => 'card-text'],
                                new Small(['class' => 'text-muted'], [
                                    $module->getName(),
                                ])
                            ),
                        ]),
                    ]);
                },
                $dashboard->getModules()
            )),
        ]);
    }

    /**
     * Get information about loaded modules.
     *
     * @param DashboardInterface $dashboard
     *
     * @return Div
     */
    public function getModules(DashboardInterface $dashboard)
    {
        return (new Div([], [
            new Div(['class' => 'card'], [
                new Div(['class' => 'card-header'], 'Available modules'),
                new Div(
                    ['class' => 'card-block'],
                    Std::map(function (Module $module) {
                        return new Div([], [
                            new HeaderSix([], $module->getName()),
                            new Paragraph([], [
                                new Bold([], 'Class Name: '),
                                get_class($module),
                                new LineBreak([]),
                                new Bold([], 'Methods: '),
                            ]),
                            new UnorderedList([], Std::map(
                                function (Method $method) {
                                    return new ListItem([], [
                                        $method->getLabel(),
                                        ' (',
                                        $method->getName(),
                                        ') -> ',
                                        $method->getControllerClassName(),
                                        '@',
                                        $method->getControllerMethodName(),
                                    ]);
                                },
                                $module->getMethods()
                            )),
                        ]);
                    }, $dashboard->getModules())
                ),
            ]),
        ]));
    }

    /**
     * Get problematic modules.
     *
     * @param DashboardInterface $dashboard
     *
     * @return Div
     */
    public function getIssues(DashboardInterface $dashboard)
    {
        $exceptions = Std::map(function (Exception $exception, $moduleName) {
            return new Div([], [
                new Div(['class' => 'card card-inverted'], [
                    new CardBlock([], [
                        new HeaderSix(['class' => 'text-muted'], $moduleName),
                        new Bold([], get_class($exception) . ': '),
                        $exception->getMessage(),
                        new Div(
                            ['class' => 'collapse p-t', 'id' => 'stack'],
                            new PreformattedText(
                                ['class' => 'pre-scrollable'],
                                $exception->getTraceAsString()
                            )
                        ),
                    ]),
                    new Div(['class' => 'card-footer text-muted'], [
                        new Row([], [
                            new Column(['medium' => 6], [
                                basename($exception->getFile()) . ':'
                                . $exception->getLine(),
                            ]),
                            new Column(
                                ['medium' => 6, 'class' => 'text-xs-right'],
                                new Button(
                                    [
                                        'href' => '#',
                                        'class' => [
                                            'btn',
                                            'btn-sm',
                                            'btn-primary-outline',
                                        ],
                                        'data-toggle' => 'collapse',
                                        'data-target' => '#stack',
                                        'aria-expanded' => 'false',
                                        'aria-controls' => '#stack',
                                    ],
                                    'Toggle stacktrace'
                                )
                            ),
                        ]),

                    ]),
                ]),
            ]);
        }, $dashboard->getFailedModules());

        return (new Div([], [
            new Div(['class' => 'card'], [
                new Div(['class' => 'card-header'], 'Module issues'),
                new Div(
                    ['class' => 'card-block'],
                    [
                        'Below you will find a list of all the modules that ',
                        'failed to load. If one or more failed to load, it is ',
                        'not necessarily a bad thing. If you do not intend to ',
                        'use the component covered by the module, you may ',
                        'safely ignore it.',
                    ]
                ),
            ]),
            new HorizontalLine([]),
            new Div([], Std::firstBias(
                count($dashboard->getFailedModules()) > 0,
                $exceptions,
                function () {
                    return new Card(
                        ['class' => 'card card-block text-center'],
                        ['All modules seem fine!']
                    );
                }
            )),
        ]));
    }
}
