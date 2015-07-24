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

use Chromabits\Illuminated\Database\Articulate\Table;
use Chromabits\Illuminated\Jobs\Interfaces\JobFactoryInterface;
use InvalidArgumentException;

/**
 * Class JobFactory.
 *
 * Build jobs. Saves the middle class. Kisses babies.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs
 */
class JobFactory implements JobFactoryInterface
{
    /**
     * Build a job.
     *
     * @param string $task
     * @param string $data
     * @param int $retries
     *
     * @return Job
     */
    public function make($task, $data = '{}', $retries = 0)
    {
        $job = new Job();

        $job->state = JobState::IDLE;
        $job->task = $task;
        $job->retries = $retries;

        // Use a safe default if nothing or an empty string.
        if (empty($data)) {
            $data = '{}';
        }

        // Try to serialize to JSON if its an object.
        if (is_object($data) || is_array($data)) {
            $data = json_encode($data);
        }

        // Check if the input is valid JSON.
        if (!$this->isJson($data)) {
            throw new InvalidArgumentException('Data is not valid JSON.');
        }

        // Check if the data will fit inside a job record.
        if (!Table::fits($data, Table::TYPE_TEXT)) {
            throw new InvalidArgumentException('Data is too large for a job.');
        }

        $job->data = $data;

        return $job;
    }

    /**
     * Copies basic parameters of a job into a new one:.
     *
     * - Task
     * - Data
     * - Retries
     *
     * @param Job $baseJob
     *
     * @return Job
     */
    public function duplicate(Job $baseJob)
    {
        $job = new Job();

        $job->state = JobState::IDLE;
        $job->task = $baseJob->task;
        $job->retries = $baseJob->retries;
        $job->data = $baseJob->data;

        return $job;
    }

    /**
     * Check if a string is valid JSON.
     *
     * @param string $string
     *
     * @return bool
     */
    protected function isJson($string)
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }
}
