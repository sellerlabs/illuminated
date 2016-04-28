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

use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Support\Std;
use SellerLabs\Nucleus\View\Bootstrap\Column;
use SellerLabs\Nucleus\View\Bootstrap\Row;
use SellerLabs\Nucleus\View\Common\Bold;
use SellerLabs\Nucleus\View\Common\Div;
use SellerLabs\Nucleus\View\Interfaces\RenderableInterface;

/**
 * Class DescriptionList.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Views
 */
class DescriptionList extends BaseObject implements RenderableInterface
{
    /**
     * @var array
     */
    protected $descriptions;

    /**
     * Add a term in the list.
     *
     * @param string $term
     * @param mixed $content
     *
     * @return $this
     */
    public function addTerm($term, $content)
    {
        $this->descriptions[$term] = $content;

        return $this;
    }

    /**
     * Render the object into a string.
     *
     * @return mixed
     */
    public function render()
    {
        return new Div([], Std::map(function ($content, $term) {
            return new Row([], [
                new Column(
                    ['medium' => 4],
                    new Bold([], $term . ':')
                ),
                new Column(
                    ['medium' => 8],
                    $content
                ),
            ]);
        }, $this->descriptions));
    }
}
