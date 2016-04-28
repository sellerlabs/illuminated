<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Jobs;

use SellerLabs\Nucleus\Foundation\Enum;

/**
 * Class JobState.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs
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
