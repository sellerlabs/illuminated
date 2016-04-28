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

use Illuminate\Config\Repository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Application;
use SellerLabs\Illuminated\Jobs\Exceptions\UnresolvableException;
use SellerLabs\Illuminated\Jobs\Interfaces\HandlerResolverInterface;
use SellerLabs\Illuminated\Jobs\Tasks\BaseTask;
use SellerLabs\Illuminated\Jobs\Tasks\GarbageCollectTask;
use SellerLabs\Nucleus\Foundation\BaseObject;

/**
 * Class HandlerResolver.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Jobs
 */
class HandlerResolver extends BaseObject implements HandlerResolverInterface
{
    /**
     * Current Laravel application.
     *
     * @var Application
     */
    protected $app;

    /**
     * Implementation of the config service.
     *
     * @var Repository
     */
    protected $config;

    /**
     * Construct an instance of a HandlerResolver.
     *
     * @param Application $app
     * @param Repository $config
     */
    public function __construct(Application $app, Repository $config)
    {
        parent::__construct();

        $this->app = $app;
        $this->config = $config;
    }

    /**
     * Attempt to resolve the task handler for a job.
     *
     * @param Job $job
     *
     * @throws UnresolvableException
     * @return BaseTask
     */
    public function resolve(Job $job)
    {
        return $this->instantiate($job->task);
    }

    /**
     * Get a mapping between task names and their handlers.
     *
     * @return mixed
     */
    protected function getMap()
    {
        return $this->config->get('jobs.map', [
            'jobs.gc' => GarbageCollectTask::class,
        ]);
    }

    /**
     * Create an instance of the handler for the provided task.
     *
     * @param string $task
     *
     * @throws UnresolvableException
     * @return mixed
     */
    public function instantiate($task)
    {
        $map = $this->getMap();

        if (!array_key_exists($task, $map)) {
            throw new UnresolvableException('Unknown task.');
        }

        try {
            $handler = $this->app->make($map[$task]);

            if (!($handler instanceof BaseTask)) {
                throw new UnresolvableException('Invalid handler.');
            }

            return $handler;
        } catch (BindingResolutionException $e) {
            throw new UnresolvableException('Unable to instantiate.', 0, $e);
        }
    }

    /**
     * Get a list of available tasks.
     *
     * @return array
     */
    public function getAvailableTasks()
    {
        return array_keys($this->getMap());
    }
}
