<?php

namespace Chromabits\Illuminated\Jobs;

use Illuminate\Config\Repository;
use Illuminate\Container\BindingResolutionException;
use Illuminate\Foundation\Application;
use Chromabits\Illuminated\Jobs\Exceptions\UnresolvableException;
use Chromabits\Illuminated\Jobs\Interfaces\HandlerResolverInterface;
use Chromabits\Illuminated\Jobs\Tasks\BaseTask;

/**
 * Class HandlerResolver
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs
 */
class HandlerResolver implements HandlerResolverInterface
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
        $this->app = $app;
        $this->config = $config;
    }

    /**
     * Attempt to resolve the task handler for a job.
     *
     * @param Job $job
     *
     * @return BaseTask
     * @throws UnresolvableException
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
        return $this->config->get('tasks.map', []);
    }

    /**
     * Create an instance of the handler for the provided task.
     *
     * @param $task
     *
     * @return mixed
     * @throws UnresolvableException
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