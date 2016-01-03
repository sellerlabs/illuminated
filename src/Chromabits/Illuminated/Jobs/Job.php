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
use Chromabits\Illuminated\Database\Articulate\Table;

/**
 * Class Job.
 *
 * A Job object represents a task that needs to be or has been performed by the
 * application. While strongly related to an queue item, a job may exist outside
 * of a queue and may continue to exist after it has been processed.
 *
 * The reasoning behind this object is to be able to perform the following tasks
 * which are not easily achieved with just queues alone:
 *
 * - Provide a UI with the status of jobs
 * - Allow users to cancel and retry jobs
 * - Allow jobs to output a short result message
 * - Groundwork for schedulable jobs which do not depend on an external system
 *   such as iron.io.
 *
 * Note: Laravel also provides a Job class, however, it only represents a job
 * that has been taken out of the queue. It provides very different
 * functionality and is ephemeral in nature.
 *
 * @property int id
 * @property string task
 * @property string data
 * @property string state
 * @property string message
 * @property bool schedulable
 * @property int attempts
 * @property int retries
 * @property string queue_connection
 * @property string queue_name
 * @property Carbon started_at
 * @property Carbon completed_at
 * @property Carbon expires_at
 * @property Carbon run_at
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs
 */
class Job extends Model
{
    /**
     * Name of the table of the model.
     *
     * @var string
     */
    protected $table = 'illuminated_jobs';

    /**
     * List of properties that should be treated as dates.
     *
     * @var array
     */
    protected $dates = ['started_at', 'completed_at', 'run_at', 'expires_at'];

    /**
     * States in which the job cna be cancelled.
     *
     * @var array
     */
    protected $cancellable = [
        JobState::IDLE,
        JobState::QUEUED,
        JobState::SCHEDULED,
    ];

    /**
     * Determine if the job is ready to be executed.
     *
     * @return bool
     */
    public function ready()
    {
        if ($this->state == JobState::QUEUED) {
            return true;
        }

        $now = Carbon::now();

        if ($this->state == JobState::SCHEDULED) {
            return $now->gte($this->run_at) && !$this->expired();
        }

        return false;
    }

    /**
     * Determine if the job as expired.
     *
     * @return bool
     */
    public function expired()
    {
        if (!$this->willExpire()) {
            return false;
        }

        $now = Carbon::now();

        return $now->gte($this->expires_at);
    }

    /**
     * Return whether the job will expire in the future.
     *
     * @return bool
     */
    public function willExpire()
    {
        return !is_null($this->expires_at);
    }

    /**
     * Get a display-friendly version of the job execution time.
     *
     * @return string
     */
    public function getExecutionTime()
    {
        if (is_null($this->started_at) || is_null($this->completed_at)) {
            return 'N/A';
        }

        return $this->completed_at->diffForHumans($this->started_at, true);
    }

    /**
     * Return whether or not this job has attempts remaining.
     *
     * @return bool
     */
    public function hasAttemptsLeft()
    {
        if (is_null($this->retries) && $this->retries < 1) {
            return false;
        }

        return ($this->retries > $this->attempts);
    }

    /**
     * Get the array version of the job data.
     *
     * @return mixed
     */
    public function getData()
    {
        if (is_null($this->data)) {
            return [];
        }

        return json_decode($this->data, true);
    }

    /**
     * Return whether the job can be cancelled.
     *
     * @return bool
     */
    public function isCancellable()
    {
        return in_array($this->state, $this->cancellable);
    }

    /**
     * Get a single key from the job data.
     *
     * The key can be in dot format.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return array_get($this->getData(), $key, $default);
    }

    /**
     * Append a line to this jobs' message.
     *
     * @param string $message
     *
     * @return Job
     */
    public function append($message)
    {
        $newMessage = $this->message . $message . "\n";

        if (Table::fits($newMessage, Table::TYPE_TEXT)) {
            $this->message = $newMessage;
        } else {
            $this->message = mb_strcut(mb_strlen($message), 0)
                . $message . "\n";
        }

        $this->save();
    }
}
