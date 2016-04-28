<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Inliner;

use SellerLabs\Illuminated\Contracts\Inliner\StyleInliner as InlinerContract;
use SellerLabs\Illuminated\Support\ServiceProvider;

/**
 * Class InlinerServiceProvider.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Inliner
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
