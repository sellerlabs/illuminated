<?php

namespace Chromabits\Illuminated\Conference\Entities;

use Chromabits\Nucleus\Foundation\BaseObject;
use Chromabits\Nucleus\Support\Std;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ConferenceContext
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Conference\Entities
 */
class ConferenceContext extends BaseObject
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * Construct an instance of a ConferenceContext.
     *
     * @param string $basePath
     */
    public function __construct($basePath)
    {
        parent::__construct();

        $this->basePath = $basePath;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Generate a URL for a dashboard module.
     *
     * Note: If the module does not have a default method, then the user will
     * see an error page.
     *
     * @param string $moduleName
     * @param array $parameters
     * @param null|bool $secure
     *
     * @return string
     */
    public function module($moduleName, $parameters = [], $secure = null)
    {
        return $this->url('/' . $moduleName . '/', $parameters, $secure);
    }

    /**
     * Generate a URL for a path inside the dashboard.
     *
     * @param string $path
     * @param array $parameters
     * @param null|bool $secure
     *
     * @return string
     */
    public function url($path = '', $parameters = [], $secure = null)
    {
        $queryString = '';

        if (count($parameters)) {
            $queryString = '?' . implode('&', Std::map(
                    function ($value, $name) {
                        return urlencode($name) . '=' . urlencode($value);
                    }
                    , $parameters
                ));
        }

        return url($this->basePath . $path, [], $secure) . $queryString;
    }

    /**
     * Generate a URL for a dashboard module method.
     *
     * @param string $moduleName
     * @param string $methodName
     * @param array $parameters
     * @param null|bool $secure
     *
     * @return string
     */
    public function method(
        $moduleName,
        $methodName,
        $parameters = [],
        $secure = null
    ) {
        return $this->url(
            '/' . $moduleName . '/' . $methodName . '/',
            $parameters,
            $secure
        );
    }
}
