<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\Chromabits\Illuminated\Jobs\Tasks;

use Chromabits\Illuminated\Jobs\Testing\TaskTestTrait;
use Tests\Chromabits\Support\IlluminatedTestCase;

/**
 * Class TaskTestCase.
 *
 * Base test case for tasks.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Jobs\Tasks
 */
abstract class TaskTestCase extends IlluminatedTestCase
{
    use TaskTestTrait;

    /**
     * Make an instance of the task being tested.
     *
     * @return mixed
     */
    abstract public function make();
}
