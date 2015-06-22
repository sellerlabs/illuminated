<?php

namespace Chromabits\Illuminated\Jobs;

use Chromabits\Nucleus\Support\Enum;

/**
 * Class JobState
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
