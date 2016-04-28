<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Conference\Views;

use SellerLabs\Illuminated\Conference\Entities\ConferenceContext;
use SellerLabs\Illuminated\Conference\Interfaces\DashboardInterface;
use SellerLabs\Illuminated\Conference\Module;
use SellerLabs\Nucleus\Support\Html;
use SellerLabs\Nucleus\Support\Std;
use SellerLabs\Nucleus\View\Bootstrap\Column;
use SellerLabs\Nucleus\View\Bootstrap\Container;
use SellerLabs\Nucleus\View\Bootstrap\Row;
use SellerLabs\Nucleus\View\Common\Anchor;
use SellerLabs\Nucleus\View\Common\Italic;
use SellerLabs\Nucleus\View\Common\ListItem;
use SellerLabs\Nucleus\View\Common\Navigation;
use SellerLabs\Nucleus\View\Common\Paragraph;
use SellerLabs\Nucleus\View\Common\Script;
use SellerLabs\Nucleus\View\Common\Small;
use SellerLabs\Nucleus\View\Common\UnorderedList;
use SellerLabs\Nucleus\View\Head\Link;
use SellerLabs\Nucleus\View\Head\Meta;
use SellerLabs\Nucleus\View\Head\Title;
use SellerLabs\Nucleus\View\Interfaces\RenderableInterface;
use SellerLabs\Nucleus\View\Interfaces\SafeHtmlProducerInterface;
use SellerLabs\Nucleus\View\SafeHtmlWrapper;

/**
 * Class ConferencePage.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Views
 */
class ConferencePage implements RenderableInterface, SafeHtmlProducerInterface
{
    /**
     * @var null|string|string[]|RenderableInterface|RenderableInterface[]
     */
    protected $sidebar;

    /**
     * @var string|string[]|RenderableInterface|RenderableInterface[]
     */
    protected $panel;

    /**
     * @var ConferenceContext
     */
    protected $context;

    /**
     * @var DashboardInterface
     */
    protected $dashboard;

    /**
     * Construct an instance of a ConferencePage.
     *
     * @param ConferenceContext $context
     * @param DashboardInterface $dashboard
     * @param string $panel
     * @param null $sidebar
     */
    public function __construct(
        ConferenceContext $context,
        DashboardInterface $dashboard,
        $panel = 'Empty.',
        $sidebar = null
    ) {
        $this->sidebar = $sidebar;
        $this->panel = $panel;
        $this->context = $context;
        $this->dashboard = $dashboard;
    }

    /**
     * Get a safe HTML version of the contents of this object.
     *
     * @return SafeHtmlWrapper
     */
    public function getSafeHtml()
    {
        return Html::safe($this->render());
    }

    /**
     * Render the object into a string.
     *
     * @return mixed
     */
    public function render()
    {
        $title = 'Illuminated';

        $dashboardUrl = $this->context->url();
        $modulesUrl = $this->context->method(
            'illuminated.conference.front',
            'modules'
        );

        $modulesDropdown = Std::foldr(
            function (NavbarDropdownFactory $factory, Module $module) {
                return $factory->addOption(
                    $this->context->module($module->getName()),
                    $module->getLabel()
                );
            },
            new NavbarDropdownFactory(),
            $this->dashboard->getModules()
        )
            ->setContent([
                new Italic(['class' => 'fa fa-rocket']),
                ' Launchpad',
            ])
            ->make();

        $innerPage = new Page(
            [
                new Container(
                    ['class' => 'p-t p-b'], [
                        new Navigation(
                            ['class' => 'navbar navbar-dark bg-inverse'],
                            [
                                new Anchor(
                                    [
                                        'class' => 'navbar-brand',
                                        'href' => $dashboardUrl,
                                    ],
                                    'Illuminated'
                                ),
                                new UnorderedList(
                                    ['class' => 'nav navbar-nav'], [
                                        new ListItem(
                                            ['class' => 'nav-item'],
                                            new Anchor(
                                                [
                                                    'href' => $dashboardUrl,
                                                    'class' => 'nav-link',
                                                ],
                                                'Home'
                                            )
                                        ),
                                        $modulesDropdown,
                                        new ListItem(
                                            ['class' => 'nav-item'],
                                            new Anchor(
                                                [
                                                    'href' => $modulesUrl,
                                                    'class' => 'nav-link',
                                                ],
                                                [
                                                    new Italic([
                                                        'class'
                                                            => 'fa fa-asterisk',
                                                    ]),
                                                    ' Meta',
                                                ]
                                            )
                                        ),
                                    ]
                                ),
                            ]
                        ),
                    ]
                ),
                $this->renderContent(),
                new Container(
                    ['class' => 'p-t p-b'], [
                        new Paragraph(
                            [], [
                                new Small(
                                    [], 'Keep building awesome stuff. 👍 '
                                ),
                            ]
                        ),
                    ]
                ),
                new Script(
                    [
                        'src' => 'https://ajax.googleapis.com/ajax/libs/jquery/'
                            . '2.1.4/jquery.min.js',
                    ]
                ),
                new Script(
                    [
                        'src' => 'https://cdn.rawgit.com/twbs/bootstrap/v4-dev/'
                            . 'dist/js/bootstrap.js',
                    ]
                ),
            ], [
                new Meta(['charset' => 'utf-8']),
                new Meta(
                    [
                        'name' => 'viewport',
                        'content' => 'width=device-width, initial-scale=1',
                    ]
                ),
                new Meta(
                    [
                        'http-equiv' => 'x-ua-compatible',
                        'content' => 'ie=edge',
                    ]
                ),
                new Title([], 'Illuminated - Conference'),
                new Link(
                    [
                        'rel' => 'stylesheet',
                        'href' => 'https://maxcdn.bootstrapcdn.com/bootstrap' .
                            '/4.0.0-alpha.2/css/bootstrap.min.css',
                    ]
                ),
                new Link(
                    [
                        'rel' => 'stylesheet',
                        'href' => 'https://maxcdn.bootstrapcdn.com/font-awesome'
                            . '/4.5.0/css/font-awesome.min.css',
                    ]
                ),
                new Link(
                    [
                        'rel' => 'stylesheet',
                        'href' => $dashboardUrl
                            . '/css/main.css',
                    ]
                ),
            ]
        );

        return $innerPage->render();
    }

    /**
     * Render the content of the panel.
     *
     * @return Container
     */
    protected function renderContent()
    {
        if ($this->sidebar === null) {
            return new Container(
                [], [
                    new Row(
                        [], [
                            new Column(
                                ['medium' => 12],
                                $this->panel
                            ),
                        ]
                    ),
                ]
            );
        }

        return new Container(
            [], [
                new Row(
                    ['class' => 'p-y-1'], [
                        new Column(
                            ['medium' => 3],
                            $this->sidebar
                        ),
                        new Column(
                            ['medium' => 9],
                            $this->panel
                        ),
                    ]
                ),
            ]
        );
    }
}
