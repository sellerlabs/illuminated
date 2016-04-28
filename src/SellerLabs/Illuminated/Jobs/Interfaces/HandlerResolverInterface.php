<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Jobs\Interfaces;

use SellerLabs\Illuminated\Jobs\Exceptions\UnresolvableException;
use SellerLabs\Illuminated\Jobs\Job;
use SellerLabs\Illuminated\Jobs\Tasks\BaseTask;

/**
 * Interface HandlerResolverInterface.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs\Interfaces
 */
interface HandlerResolverInterface
{
    /**
     * Attempt to resolve the task handler for a job.
     *
     * @param Job $job
     *
     * @throws UnresolvableException
     * @return BaseTask
     */
    public function resolve(Job $job);

    /**
     * Get a list of available tasks.
     *
     * @return array
     */
    public function getAvailableTasks();

    /**
     * Create an instance of the handler for the provided task.
     *
     * @param string $task
     *
     * @throws UnresolvableException
     * @return BaseTask
     */
    public function instantiate($task);
}
