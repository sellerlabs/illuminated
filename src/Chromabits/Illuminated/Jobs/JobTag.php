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

use Carbon\Carbon;
use Chromabits\Illuminated\Database\Articulate\Model;

/**
 * Class JobTag
 *
 * A simple string tag for a job. Useful for keeping track of multiple jobs
 * that belong to the same process.
 *
 * For example, a process could be split among many jobs. All the jobs that are
 * part of this process could be tagged with a process ID, which would allow
 * other parts of the applications to query the state of the process of a whole
 * rather than just a single jobs.
 *
 * @property int id
 * @property int job_id
 * @property string name
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs
 */
class JobTag extends Model
{
    /**
     * Name of the table of the model.
     *
     * @var string
     */
    protected $table = 'illuminated_job_tags';

    /**
     * Every tag is attached to a job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
