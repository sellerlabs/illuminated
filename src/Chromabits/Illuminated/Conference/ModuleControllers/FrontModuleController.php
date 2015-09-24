<?php

namespace Chromabits\Illuminated\Conference\ModuleControllers;

use Chromabits\Illuminated\Http\BaseController;

/**
 * Class FrontModuleController
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Conference\ModuleControllers
 */
class FrontModuleController extends BaseController
{
    public function getIndex()
    {
        return 'Hello World';
    }
}