<?php

namespace Chromabits\Illuminated\Raml;

use Chromabits\Illuminated\Raml\Interfaces\RamlEncoderInterface;
use Chromabits\Illuminated\Support\ServiceMapProvider;

/**
 * Class RamlServiceProvider.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Raml
 */
class RamlServiceProvider extends ServiceMapProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @var array
     */
    protected $map = [
        RamlEncoderInterface::class => RamlEncoder::class,
    ];
}