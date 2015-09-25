<?php

namespace Chromabits\Illuminated\Conference\Modules;

use Chromabits\Illuminated\Conference\Module;
use Chromabits\Illuminated\Conference\ModuleControllers\HttpModuleController;

/**
 * Class HttpModule
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Conference\Modules
 */
class HttpModule extends Module
{
    public function __construct()
    {
        parent::__construct();

        $this->register(
            'index',
            HttpModuleController::class,
            'getIndex',
            'All Routes'
        );

        $this->register(
            'middleware',
            HttpModuleController::class,
            'getRouterMiddleware',
            'Middleware'
        );

        $this->register(
            'patterns',
            HttpModuleController::class,
            'getPatterns',
            'Patterns'
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'illuminated.conference.http';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'HTTP';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Provides information about Laravel\'s HTTP components';
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