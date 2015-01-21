<?php

namespace Tests\Chromabits\Illuminated\Inliner;

use Chromabits\Illuminated\Inliner\StyleInliner;
use Chromabits\Nucleus\Testing\TestCase;

/**
 * Class StyleInlinerTest
 *
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
            'exclude_media_queries' => false
        ]);

        $this->assertInstanceOf([
            'Chromabits\Illuminated\Inliner\StyleInliner'
        ], $inliner);
    }

    public function testConstructorWithNoOptions()
    {
        $inliner = new StyleInliner(['some/path']);

        $this->assertInstanceOf([
            'Chromabits\Illuminated\Inliner\StyleInliner'
        ], $inliner);
    }

    public function testInline()
    {
        $inliner = new StyleInliner([__DIR__ . '/../../../resources/']);

        $output = $inliner->inline('<p class="my-style-one">Hi</p>', 'testing');

        $this->assertTrue(false !== strpos($output, "style=\"font-size: 43px;\""));
    }
}
