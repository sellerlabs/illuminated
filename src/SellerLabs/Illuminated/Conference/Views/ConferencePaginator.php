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

use Illuminate\Contracts\Pagination\Paginator;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Support\Std;
use SellerLabs\Nucleus\View\Bootstrap\CardBlock;
use SellerLabs\Nucleus\View\Interfaces\RenderableInterface;

/**
 * Class ConferencePaginator.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Views
 */
class ConferencePaginator extends BaseObject implements
    RenderableInterface
{
    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * Construct an instance of a ConferencePaginator.
     *
     * @param Paginator $paginator
     */
    public function __construct(Paginator $paginator)
    {
        parent::__construct();

        $this->paginator = $paginator;
    }

    /**
     * Render the object into a string.
     *
     * @return mixed
     */
    public function render()
    {
        return Std::firstBias(
            $this->paginator->hasPages(),
            function () {
                return new CardBlock(
                    ['class' => 'card-block text-center'],
                    new BootstrapFourPaginatorPresenter($this->paginator)
                );
            },
            ''
        );
    }
}
