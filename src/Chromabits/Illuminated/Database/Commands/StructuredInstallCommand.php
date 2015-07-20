<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Database\Commands;

use Illuminate\Database\Console\Migrations\InstallCommand;

/**
 * Class StructuredInstallCommand
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Commands
 */
class StructuredInstallCommand extends InstallCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'structured:install';
}
