<?php

/**
 * Copyright 2015, Eduardo Trujillo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Laravel Helpers package
 */

namespace Tests\Chromabits\Illuminated\Inliner;

use Chromabits\Illuminated\Inliner\StyleInliner;
use Chromabits\Nucleus\Testing\TestCase;
use Mockery;

/**
 * Class StyleInlinerTest
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Tests\Chromabits\Illuminated\Inliner
 */
class StyleInlinerTest extends TestCase
{
    public function testConstructor()
    {
        $inliner = new StyleInliner(['some/path'], [
            'cleanup' => false,
            'use_inline_styles_block' => false,
            'strip_original_tags' => false,
            'exclude_media_queries' => false,
        ]);

        $this->assertInstanceOf([
            'Chromabits\Illuminated\Inliner\StyleInliner',
        ], $inliner);
    }

    public function testConstructorWithNoOptions()
    {
        $inliner = new StyleInliner(['some/path']);

        $this->assertInstanceOf([
            'Chromabits\Illuminated\Inliner\StyleInliner',
        ], $inliner);
    }

    public function testInline()
    {
        $inliner = new StyleInliner([__DIR__ . '/../../../resources/']);

        $output = $inliner->inline('<p class="my-style-one">Hi</p>', 'testing');

        $this->assertTrue(false !== strpos($output, "style=\"font-size: 43px;\""));
    }

    public function testInlineWithView()
    {
        $mock = Mockery::mock('Illuminate\Contracts\View\View');

        $mock->shouldReceive('render')->andReturn('<p class="my-style-one">Hi</p>');

        $inliner = new StyleInliner([__DIR__ . '/../../../resources/']);

        $output = $inliner->inline($mock, 'testing');

        $this->assertTrue(false !== strpos($output, "style=\"font-size: 43px;\""));
    }

    public function testInlineAndSend()
    {
        $mock = Mockery::mock('Illuminate\Contracts\View\View');
        $mock->shouldReceive('render')->andReturn('<p class="my-style-one">Hi</p>');

        $mailer = Mockery::mock('Illuminate\Contracts\Mail\Mailer');
        $mailer->shouldReceive('send');

        $inliner = new StyleInliner([__DIR__ . '/../../../resources/']);

        $inliner->inlineAndSend($mailer, $mock, 'testing', function ($message) {

        });

        $mailer->shouldHaveReceived('send', [
            Mockery::type('array'),
            [],
            Mockery::type('callable'),
        ]);
    }
}
