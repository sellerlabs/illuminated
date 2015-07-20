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

use Chromabits\Nucleus\Testing\TestCase;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;

/**
 * Class InlinerServiceProviderTest
 *
 * @package Chromabits\Illuminated\Inliner
 */
class InlinerServiceProviderTest extends TestCase
{
    /**
     * @var Application
     */
    protected $app;

    protected function setUp()
    {
        parent::setUp();

        $this->app = new Application();

        $this->app->bind('config', function () {
            return new Repository([
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
            ]);
        });
    }

    public function testRegister()
    {
        $provider = new InlinerServiceProvider($this->app);

        $provider->register();

        $this->assertTrue($this->app->bound('Chromabits\Illuminated\Contracts\Inliner\StyleInliner'));

        $this->assertInstanceOf(
            'Chromabits\Illuminated\Contracts\Inliner\StyleInliner',
            $this->app->make('Chromabits\Illuminated\Contracts\Inliner\StyleInliner')
            );
    }

    public function testProvides()
    {
        $provider = new InlinerServiceProvider($this->app);

        $this->assertInternalType('array', $provider->provides());
    }
}
