<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Jobs\Tasks;

use SellerLabs\Illuminated\Jobs\Testing\TaskTestTrait;
use Tests\SellerLabs\Support\IlluminatedTestCase;

/**
 * Class TaskTestCase.
 *
 * Base test case for tasks.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Jobs\Tasks
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
