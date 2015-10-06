<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Conference\Views;

use Chromabits\Nucleus\Support\Html;
use Chromabits\Nucleus\View\Interfaces\RenderableInterface;
use Chromabits\Nucleus\View\Interfaces\SafeHtmlProducerInterface;
use Chromabits\Nucleus\View\Page\Body;
use Chromabits\Nucleus\View\Page\Doctype;
use Chromabits\Nucleus\View\Page\Head;
use Chromabits\Nucleus\View\Page\Html as HtmlTag;
use Chromabits\Nucleus\View\SafeHtmlWrapper;

/**
 * Class Page.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Conference\Views
 */
class Page implements RenderableInterface, SafeHtmlProducerInterface
{
    /**
     * @var array
     */
    protected $children;

    /**
     * @var array
     */
    protected $headerChildren;

    /**
     * Construct an instance of a Page.
     *
     * @param array $children
     * @param array $headerChildren
     */
    public function __construct($children = [], $headerChildren = [])
    {
        $this->children = $children;
        $this->headerChildren = $headerChildren;
    }

    /**
     * Render the object into a string.
     *
     * @return mixed
     */
    public function render()
    {
        return (new Doctype())->render() . (new HtmlTag([], [
            new Head([], $this->headerChildren),
            new Body([], $this->children),
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
