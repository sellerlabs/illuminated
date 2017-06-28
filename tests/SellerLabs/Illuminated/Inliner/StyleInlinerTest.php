<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace Tests\SellerLabs\Illuminated\Inliner;

use Mockery;
use SellerLabs\Illuminated\Inliner\StyleInliner;
use SellerLabs\Nucleus\Testing\TestCase;

/**
 * Class StyleInlinerTest.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package Tests\SellerLabs\Illuminated\Inliner
 */
class StyleInlinerTest extends TestCase
{
    public function testConstructor()
    {
        $inliner = new StyleInliner(
            ['some/path'],
            [
                'cleanup' => false,
                'use_inline_styles_block' => false,
                'strip_original_tags' => false,
                'exclude_media_queries' => false,
            ]
        );

        $this->assertInstanceOf(
            [
                'SellerLabs\Illuminated\Inliner\StyleInliner',
            ],
            $inliner
        );
    }

    public function testConstructorWithNoOptions()
    {
        $inliner = new StyleInliner(['some/path']);

        $this->assertInstanceOf(
            [
                'SellerLabs\Illuminated\Inliner\StyleInliner',
            ],
            $inliner
        );
    }

    public function testInline()
    {
        $inliner = new StyleInliner([__DIR__ . '/../../../resources/']);

        $output = $inliner->inline('<p class="my-style-one">Hi</p>', 'testing');

        $this->assertTrue(
            false !== strpos($output, 'style="font-size: 43px;"')
        );
    }

    public function testInlineWithView()
    {
        $mock = Mockery::mock('Illuminate\Contracts\View\View');

        $mock->shouldReceive('render')->andReturn(
            '<p class="my-style-one">Hi</p>'
        );

        $inliner = new StyleInliner([__DIR__ . '/../../../resources/']);

        $output = $inliner->inline($mock, 'testing');

        $this->assertTrue(
            false !== strpos($output, 'style="font-size: 43px;"')
        );
    }

    public function testInlineAndSend()
    {
        $mock = Mockery::mock('Illuminate\Contracts\View\View');
        $mock->shouldReceive('render')->andReturn(
            '<p class="my-style-one">Hi</p>'
        );

        $mailer = Mockery::mock('Illuminate\Contracts\Mail\Mailer');
        $mailer->shouldReceive('send');

        $inliner = new StyleInliner([__DIR__ . '/../../../resources/']);

        $inliner->inlineAndSend(
            $mailer,
            $mock,
            'testing',
            function ($message) {

            }
        );

        $mailer->shouldHaveReceived(
            'send',
            [
                Mockery::type('array'),
                [],
                Mockery::type('callable'),
            ]
        );
    }
}
