<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Jobs\Modules;

use Chromabits\Illuminated\Conference\Module;
use Chromabits\Illuminated\Jobs\Controllers\JobsModuleController;
use Chromabits\Illuminated\Jobs\Interfaces\JobSchedulerInterface;
use Chromabits\Nucleus\Exceptions\CoreException;
use Illuminate\Contracts\Container\Container;

/**
 * Class JobsModule.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Jobs\Modules
 */
class JobsModule extends Module
{
    /**
     * @var Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        parent::__construct();

        $this->container = $container;

        $this->register(
            'index',
            JobsModuleController::class,
            'getIndex',
            'All Jobs'
        );

        $this->register(
            'scheduled',
            JobsModuleController::class,
            'getScheduled',
            'Scheduled Jobs'
        );

        $this->register(
            'queued',
            JobsModuleController::class,
            'getQueued',
            'Queued Jobs'
        );

        $this->register(
            'failed',
            JobsModuleController::class,
            'getFailed',
            'Failed Jobs'
        );

        $this->register(
            'reference',
            JobsModuleController::class,
            'getReference',
            'Reference'
        );

        $this->register(
            'reference.single',
            JobsModuleController::class,
            'getReferenceSingle',
            'Single Task Reference',
            'GET',
            true
        );
    }

    public function boot()
    {
        parent::boot();

        if (!$this->container->bound(JobSchedulerInterface::class)) {
            throw new CoreException(
                'This module requires the Jobs component to be loaded. ' .
                'Please make sure that `JobsServiceProvider` ' .
                'is in your application\'s `config/app.php` file.'
            );
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'illuminated.jobs';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Jobs';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Queue and view jobs status.';
    }

    /**
     * Get the name of the default method.
     *
     * @return string|null
     */
    public function getDefaultMethodName()
    {
        return 'index';
    }
}
