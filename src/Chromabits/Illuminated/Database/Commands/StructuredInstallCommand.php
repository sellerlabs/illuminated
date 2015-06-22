<?php

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
