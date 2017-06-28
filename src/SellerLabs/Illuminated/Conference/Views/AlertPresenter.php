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

use SellerLabs\Illuminated\Alerts\Alert;
use SellerLabs\Illuminated\Contracts\Alerts\AlertManager;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\Support\Std;
use SellerLabs\Nucleus\View\Common\Div;
use SellerLabs\Nucleus\View\Interfaces\RenderableInterface;

/**
 * Class AlertPresenter.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Conference\Views
 */
class AlertPresenter extends BaseObject implements RenderableInterface
{
    /**
     * @var array
     */
    protected $alerts;

    /**
     * Construct an instance of a AlertPresenter.
     *
     * @param AlertManager $manager
     */
    public function __construct(AlertManager $manager)
    {
        parent::__construct();

        $this->alerts = $manager->all();
    }

    /**
     * Render the object into a string.
     *
     * @return mixed
     */
    public function render()
    {
        return new Div([], Std::map(function (Alert $alert) {
            $classes = ['alert'];

            switch ($alert->getType()) {
                case Alert::TYPE_SUCCESS:
                    $classes[] = 'alert-success';
                    break;
                case Alert::TYPE_WARNING:
                    $classes[] = 'alert-warning';
                    break;
                case Alert::TYPE_INFO:
                    $classes[] = 'alert-info';
                    break;
                case Alert::TYPE_ERROR:
                case Alert::TYPE_VALIDATION:
                    $classes[] = 'alert-danger';
                    break;
            }

            return new Div(['class' => $classes], $alert->getContent());
        }, $this->alerts));
    }
}
