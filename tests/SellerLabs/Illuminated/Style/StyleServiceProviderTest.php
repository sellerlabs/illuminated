<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Style;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use SellerLabs\Illuminated\Testing\ServiceProviderTestCase;
use SellerLabs\Standards\Console\CleanCommand;
use SellerLabs\Standards\Console\FixCommand;
use SellerLabs\Standards\Console\FormatCommand;
use SellerLabs\Standards\Console\InitCommand;
use SellerLabs\Standards\Console\ValidateCommand;

/**
 * Class StyleServiceProviderTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Style
 */
class StyleServiceProviderTest extends ServiceProviderTestCase
{
    protected $commands = [
        InitCommand::class,
        FixCommand::class,
        FormatCommand::class,
        CleanCommand::class,
        ValidateCommand::class,
    ];

    /**
     * Make an instance of the service provider being tested.
     *
     * @param Application $app
     *
     * @return ServiceProvider
     */
    public function make(Application $app)
    {
        return new StyleServiceProvider($app);
    }
}
