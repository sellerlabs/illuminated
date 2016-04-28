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

use SellerLabs\Illuminated\Support\ServiceMapProvider;
use SellerLabs\Standards\Console\CleanCommand;
use SellerLabs\Standards\Console\FixCommand;
use SellerLabs\Standards\Console\FormatCommand;
use SellerLabs\Standards\Console\InitCommand;
use SellerLabs\Standards\Console\ValidateCommand;

/**
 * Class StyleServiceProvider.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Style
 */
class StyleServiceProvider extends ServiceMapProvider
{
    protected $defer = false;

    protected $commands = [
        InitCommand::class,
        FixCommand::class,
        FormatCommand::class,
        CleanCommand::class,
        ValidateCommand::class,
    ];
}
