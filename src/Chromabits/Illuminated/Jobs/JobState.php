<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Jobs;

use Chromabits\Nucleus\Foundation\Enum;

/**
 * Class JobState.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs
 */
class JobState extends Enum
{
    const IDLE = 'idle';
    const SCHEDULED = 'scheduled';
    const QUEUED = 'queued';
    const RUNNING = 'running';
    const COMPLETE = 'complete';
    const FAILED = 'failed';
    const CANCELLED = 'cancelled';
}
