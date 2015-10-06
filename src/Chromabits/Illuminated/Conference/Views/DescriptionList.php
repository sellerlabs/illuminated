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

use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Support\Std;
use Chromabits\Nucleus\View\Bootstrap\Column;
use Chromabits\Nucleus\View\Bootstrap\Row;
use Chromabits\Nucleus\View\Common\Bold;
use Chromabits\Nucleus\View\Common\Div;
use Chromabits\Nucleus\View\Interfaces\RenderableInterface;

/**
 * Class DescriptionList.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Conference\Views
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
