<?php

namespace Chromabits\Illuminated\Bootstrap\Interfaces;

use Illuminate\Contracts\Foundation\Application;

/**
 * Interface BootstrapperInterface
 *
 * A bootstrapper bootstraps a part of the application.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Bootstrap\Interfaces
 */
interface BootstrapperInterface
{
    /**
     * Bootstrap the given application.
     *
     * @param Application $app
     */
    public function bootstrap(Application $app);
}
