<?php

/**
 * Copyright 2015, Eduardo Trujillo <ed@chromabits.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Chromabits\Illuminated\Inliner;

use Chromabits\Illuminated\Contracts\Inliner\StyleInliner as InlinerContract;
use Chromabits\Illuminated\Support\ServiceProvider;

/**
 * Class InlinerServiceProvider.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Inliner
 */
class InlinerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     */
    public function register()
    {
        $this->app->bind(
            InlinerContract::class,
            function ($app) {
                return new StyleInliner(
                    $app['config']['inliner.paths.stylesheets'],
                    $app['config']['inliner.options']
                );
            }
        );
    }

    /**
     * Return provide services in an array.
     *
     * @return array
     */
    public function provides()
    {
        return [
            InlinerContract::class,
        ];
    }
}
