<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Database\Views;

use SellerLabs\Illuminated\Database\Interfaces\StructuredStatusInterface;
use SellerLabs\Illuminated\Database\Migrations\StatusReport;
use SellerLabs\Nucleus\Foundation\BaseObject;
use SellerLabs\Nucleus\View\Bootstrap\Card;
use SellerLabs\Nucleus\View\Bootstrap\CardBlock;
use SellerLabs\Nucleus\View\Bootstrap\Column;
use SellerLabs\Nucleus\View\Bootstrap\Row;
use SellerLabs\Nucleus\View\Common\HeaderOne;
use SellerLabs\Nucleus\View\Common\Paragraph;
use SellerLabs\Nucleus\View\Common\Small;
use SellerLabs\Nucleus\View\Interfaces\RenderableInterface;

/**
 * Class ConferenceStructuredStatisticsPresenter.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Database\Views
 */
class ConferenceStructuredStatisticsPresenter extends BaseObject implements
    RenderableInterface
{
    /**
     * @var StructuredStatusInterface
     */
    protected $report;

    /**
     * Construct an instance of a ConferenceStructuredStatisticsPresenter.
     *
     * @param StatusReport $report
     */
    public function __construct(StatusReport $report)
    {
        parent::__construct();

        $this->report = $report;
    }

    /**
     * Render the object into a string.
     *
     * @return mixed
     */
    public function render()
    {
        return new Row([], [
            new Column(['medium' => 6, 'class' => 'text-xs-center'], [
                new Card([], new CardBlock([], [
                    new HeaderOne(
                        ['class' => 'display-3'],
                        vsprintf('%d', [
                            count($this->report->getRan()),
                        ])
                    ),
                    new Paragraph([],
                        new Small(['class' => 'text-uppercase text-muted'], [
                            'Migrations ran so far',
                        ])
                    ),
                ])),
            ]),
            new Column(['medium' => 6, 'class' => 'text-xs-center'], [
                new Card([], new CardBlock([], [
                    new HeaderOne(
                        ['class' => 'display-3'],
                        vsprintf('%d', [
                            count($this->report->getIdle()),
                        ])
                    ),
                    new Paragraph([],
                        new Small(['class' => 'text-uppercase text-muted'], [
                            'Pending to be ran',
                        ])
                    ),
                ])),
            ]),
        ]);
    }
}
