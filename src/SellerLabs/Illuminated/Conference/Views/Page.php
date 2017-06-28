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

use SellerLabs\Nucleus\Support\Html;
use SellerLabs\Nucleus\View\Interfaces\RenderableInterface;
use SellerLabs\Nucleus\View\Interfaces\SafeHtmlProducerInterface;
use SellerLabs\Nucleus\View\Page\Body;
use SellerLabs\Nucleus\View\Page\Doctype;
use SellerLabs\Nucleus\View\Page\Head;
use SellerLabs\Nucleus\View\Page\Html as HtmlTag;
use SellerLabs\Nucleus\View\SafeHtmlWrapper;

/**
 * Class Page.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Views
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
