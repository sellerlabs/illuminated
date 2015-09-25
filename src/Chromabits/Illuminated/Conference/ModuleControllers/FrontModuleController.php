<?php

namespace Chromabits\Illuminated\Conference\ModuleControllers;

use Chromabits\Illuminated\Conference\Entities\ConferenceContext;
use Chromabits\Illuminated\Conference\Interfaces\DashboardInterface;
use Chromabits\Illuminated\Conference\Method;
use Chromabits\Illuminated\Conference\Module;
use Chromabits\Illuminated\Http\BaseController;
use Chromabits\Nucleus\Support\Std;
use Chromabits\Nucleus\View\Common\Bold;
use Chromabits\Nucleus\View\Common\Div;
use Chromabits\Nucleus\View\Common\HeaderFive;
use Chromabits\Nucleus\View\Common\HeaderFour;
use Chromabits\Nucleus\View\Common\HeaderSix;
use Chromabits\Nucleus\View\Common\LineBreak;
use Chromabits\Nucleus\View\Common\ListItem;
use Chromabits\Nucleus\View\Common\Paragraph;
use Chromabits\Nucleus\View\Common\Small;
use Chromabits\Nucleus\View\Common\UnorderedList;
use Chromabits\Nucleus\View\Common\Anchor;

/**
 * Class FrontModuleController
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Conference\ModuleControllers
 */
class FrontModuleController extends BaseController
{
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
                        'component.'
                    ]),
                    ' Here you will find a couple of built-in modules which ',
                    'you can use to debug and understand your application. ',
                    'However, you can also add your own modules for more ',
                    'specific tools!'
                ])
            ]),
            new Div(['class' => 'row'], [
                new Div(['class' => 'col-sm-12'], [
                    new HeaderFive(['class' => 'p-b p-t'], 'Launchpad:'),
                ])
            ]),
            $this->renderLaunchpad($dashboard, $context),
        ]));
    }

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
                                        $module->getLabel()
                                    )
                                ]
                            ),
                            new Paragraph(
                                ['class' => 'card-text'],
                                $module->getDescription()
                            ),
                            new Paragraph(
                                ['class' => 'card-text'],
                                new Small(['class' => 'text-muted'], [
                                    $module->getName()
                                ])
                            )
                        ])
                    ]);
                },
                $dashboard->getModules()
            ))
        ]);
    }

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
                                        $method->getControllerMethodName()
                                    ]);
                                },
                                $module->getMethods()
                            ))
                        ]);
                    }, $dashboard->getModules())
                )
            ])
        ]));
    }
}