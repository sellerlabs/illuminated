<?php

/**
 * Copyright 2015, Eduardo Trujillo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Laravel Helpers package
 */

namespace Chromabits\Illuminated\Inliner;

use Chromabits\Illuminated\Contracts\Inliner\StyleInliner as InlinerContract;
use Illuminate\Support\ServiceProvider;

/**
 * Class InlinerServiceProvider
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
     * @return void
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
     * Return provide services in an array
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
