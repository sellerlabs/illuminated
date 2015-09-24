<?php

namespace Chromabits\Illuminated\Conference\Views;

use Chromabits\Nucleus\Support\Html;
use Chromabits\Nucleus\View\Bootstrap\Column;
use Chromabits\Nucleus\View\Bootstrap\Container;
use Chromabits\Nucleus\View\Bootstrap\Row;
use Chromabits\Nucleus\View\Common\Anchor;
use Chromabits\Nucleus\View\Common\Navigation;
use Chromabits\Nucleus\View\Common\Paragraph;
use Chromabits\Nucleus\View\Common\Small;
use Chromabits\Nucleus\View\Head\Link;
use Chromabits\Nucleus\View\Head\Meta;
use Chromabits\Nucleus\View\Head\Title;
use Chromabits\Nucleus\View\Interfaces\RenderableInterface;
use Chromabits\Nucleus\View\Interfaces\SafeHtmlProducerInterface;
use Chromabits\Nucleus\View\SafeHtmlWrapper;

/**
 * Class ConferencePage
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Conference\Views
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
     * Construct an instance of a ConferencePage.
     * @param string $panel
     * @param null $sidebar
     */
    public function __construct($panel = 'Empty.', $sidebar = null)
    {
        $this->sidebar = $sidebar;
        $this->panel = $panel;
    }

    /**
     * Render the content of the panel.
     *
     * @return Container
     */
    protected function renderContent()
    {
        if ($this->sidebar === null) {
            return new Container([], [
                new Row([], [
                    new Column(
                        ['medium' => 12],
                        $this->panel
                    )
                ]),
            ]);
        }

        return new Container([], [
            new Row([], [
                new Column(
                    ['medium' => 4],
                    $this->sidebar
                ),
                new Column(
                    ['medium' => 8],
                    $this->panel
                )
            ]),
        ]);
    }

    /**
     * Render the object into a string.
     *
     * @return mixed
     */
    public function render()
    {
        return (new Page([
            new Container(['class' => 'p-t p-b'], [
                new Navigation(
                    ['class' => 'navbar navbar-dark bg-inverse'],
                    [new Anchor(
                        ['class' => 'navbar-brand', 'href' => '#'],
                        'Illuminated'
                    )]
                ),
            ]),
            $this->renderContent(),
            new Container(['class' => 'p-t p-b'], [
                new Paragraph([], [
                    new Small([], 'Keep building awesome stuff. 👍 ')
                ])
            ]),
        ], [
            new Meta(['charset' => 'utf-8']),
            new Meta([
                'name' => 'viewport',
                'content' => 'width=device-width, initial-scale=1',
            ]),
            new Meta([
                'http-equiv' => 'x-ua-compatible',
                'content' => 'ie=edge',
            ]),
            new Title([], 'Illuminated - Conference'),
            new Link([
                'rel' => 'stylesheet',
                'href' => 'https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist'
                    . '/css/bootstrap.css'
            ])
        ]))->render();
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
}