<?php

namespace Chromabits\Illuminated\Http\Entities;

use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Meditation\Arguments;
use Chromabits\Nucleus\Meditation\Boa;

/**
 * Class ResourceMethod
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Http\Entities
 */
class ResourceMethod extends BaseObject
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $verb;

    /**
     * @var string
     */
    protected $path;

    /**
     * Construct an instance of a ResourceMethod.
     *
     * @param string $method
     * @param string $verb
     * @param string $path
     */
    public function __construct($method, $verb, $path)
    {
        parent::__construct();

        Arguments::contain(
            Boa::string(),
            Boa::string(),
            Boa::string()
        )->check($method, $verb, $path);

        $this->method = $method;
        $this->verb = $verb;
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
