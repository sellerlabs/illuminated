<?php

namespace Chromabits\Illuminated\Conference\Modules;

use Chromabits\Illuminated\Conference\Module;
use Chromabits\Illuminated\Conference\ModuleControllers\FrontModuleController;

/**
 * Class FrontModule
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Conference\Modules
 */
class FrontModule extends Module
{
    /**
     * Construct an instance of a FrontModule.
     */
    public function __construct()
    {
        parent::__construct();

        $this->register(
            'index',
            FrontModuleController::class,
            'getIndex',
            'Home'
        );

        $this->register(
            'modules',
            FrontModuleController::class,
            'getModules',
            'Modules'
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'illuminated.conference.front';
    }

    /**
     * @return string|null
     */
    public function getDefaultMethodName()
    {
        return 'index';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Home';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Provides an overview panel for Conference.';
    }
}