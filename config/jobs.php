<?php

use Chromabits\Illuminated\Jobs\Tasks\GarbageCollectTask;

/**
 * Configuration for the job component.
 */
return [
    /**
     * Default queue properties for all jobs.
     *
     * Please refer to the `Job` class for assigning individual jobs to specific
     * queues.
     */
    'queue' => [
        // Name of the queue connection to use as defined in the queue.php
        // configuration file. If left to null, the default connection will be
        // used.
        'connection' => null,

        // Queue to use for the job. This can be a simple string or a SQS
        // address string. If left to null, the default queue for the
        // connection will be used.
        'id' => null,
    ],

    /**
     * The task map is a list of task name and handler pairings.
     *
     * While the same handler can be referenced by different tasks, you may not
     * define multiple handlers for one task.
     *
     * Additionally, a handler should properly extend the BaseTask class.
     */
    'map' => [
        'jobs.gc' => GarbageCollectTask::class,
    ],
];
