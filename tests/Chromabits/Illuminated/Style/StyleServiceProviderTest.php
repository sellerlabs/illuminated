<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Style;

use Chromabits\Illuminated\Testing\ServiceProviderTestCase;
use Chromabits\Standards\Console\CleanCommand;
use Chromabits\Standards\Console\FixCommand;
use Chromabits\Standards\Console\FormatCommand;
use Chromabits\Standards\Console\InitCommand;
use Chromabits\Standards\Console\ValidateCommand;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Class StyleServiceProviderTest.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Style
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
