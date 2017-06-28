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

use SellerLabs\Nucleus\View\Bootstrap\DropdownFactory;
use SellerLabs\Nucleus\View\Common\Anchor;
use SellerLabs\Nucleus\View\Common\Div;
use SellerLabs\Nucleus\View\Common\Italic;

/**
 * Class NavbarDropdownFactory.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Views
 */
class NavbarDropdownFactory extends DropdownFactory
{
    /**
     * @var mixed
     */
    protected $content;

    /**
     * Construct an instance of a NavbarDropdownFactory.
     */
    public function __construct()
    {
        parent::__construct();

        $this->content = new Italic(['class' => 'fa fa-ellipsis-h']);
    }

    /**
     * Set content.
     *
     * @param mixed $content
     *
     * @return NavbarDropdownFactory
     */
    public function setContent($content)
    {
        $copy = clone $this;

        $copy->content = $content;

        return $copy;
    }

    /**
     * Build the dropdown element.
     *
     * @return Div
     */
    public function make()
    {
        $menuClasses = ['dropdown-menu'];

        if ($this->right) {
            $menuClasses[] = 'dropdown-menu-right';
        }

        return new Div(['class' => 'dropdown nav-item'], [
            new Anchor(
                [
                    'id' => $this->hash,
                    'class' => 'nav-link',
                    'data-toggle' => 'dropdown',
                    'aria-haspopup' => 'true',
                    'aria-expanded' => 'false',
                ],
                $this->content
            ),
            new Div(
                [
                    'class' => $menuClasses,
                    'aria-labelledby' => $this->hash,
                ],
                $this->options
            ),
        ]);
    }
}
