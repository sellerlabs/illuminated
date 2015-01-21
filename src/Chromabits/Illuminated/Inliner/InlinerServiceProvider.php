<?php

namespace Chromabits\Illuminated\Inliner;

use Illuminate\Support\ServiceProvider;

/**
 * Class InlinerServiceProvider
 *
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
        $this->app->bind('Chromabits\Illuminated\Contracts\Inliner\StyleInliner', function ($app) {
            return new StyleInliner(
                $app['config']['inliner.paths.stylesheets'],
                $app['config']['inliner.options']
            );
        });
    }
}
