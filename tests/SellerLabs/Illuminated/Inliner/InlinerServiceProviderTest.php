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

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use SellerLabs\Nucleus\Testing\TestCase;

/**
 * Class InlinerServiceProviderTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Inliner
 */
class InlinerServiceProviderTest extends TestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Setup the test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->app = new Application();

        $this->app->bind(
            'config',
            function () {
                return new Repository(
                    [
                        'inliner' => [
                            'paths' => [
                                'stylesheets' => [
                                    'some/path/n/stuff',
                                ],
                            ],
                            'options' => [
                                'cleanup' => false,
                                'use_inline_styles_block' => false,
                                'strip_original_tags' => false,
                                'exclude_media_queries' => false,
                            ],
                        ],
                    ]
                );
            }
        );
    }

    public function testRegister()
    {
        $provider = new InlinerServiceProvider($this->app);

        $provider->register();

        $this->assertTrue(
            $this->app->bound(
                'SellerLabs\Illuminated\Contracts\Inliner\StyleInliner'
            )
        );

        $this->assertInstanceOf(
            'SellerLabs\Illuminated\Contracts\Inliner\StyleInliner',
            $this->app->make(
                'SellerLabs\Illuminated\Contracts\Inliner\StyleInliner'
            )
        );
    }

    public function testProvides()
    {
        $provider = new InlinerServiceProvider($this->app);

        $this->assertInternalType('array', $provider->provides());
    }
}
