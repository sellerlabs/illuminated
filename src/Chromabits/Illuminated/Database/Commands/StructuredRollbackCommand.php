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

use Chromabits\Illuminated\Database\Interfaces\StructuredMigratorInterface;
use Illuminate\Console\Command;
use Illuminate\Database\Console\Migrations\RollbackCommand;

/**
 * Class StructuredRollbackCommand
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Database\Commands
 */
class StructuredRollbackCommand extends RollbackCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'structured:rollback';

    /**
     * Construct an instance of a StructuredRollbackCommand.
     *
     * @param StructuredMigratorInterface $migrator
     */
    public function __construct(StructuredMigratorInterface $migrator)
    {
        Command::__construct();

        $this->migrator = $migrator;
    }
}
