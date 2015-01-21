<?php

namespace Chromabits\Illuminated\Contracts\Inliner;

/**
 * Interface StyleInliner
 *
 * Converts CSS/HTML file combinations into a single string
 * of HTML with inlined styles, which is useful for rendering the
 * contents of an email since many services strip style blocks
 * from emails.
 *
 * @package Chromabits\Illuminated\Contracts\Inliner
 */
interface StyleInliner
{
    /**
     * Inline CSS stylesheet into a HTML string or Laravel view
     *
     * If a Laravel view is provided, the view will be rendered
     *
     * @param string|\Illuminate\Contracts\View\View $content
     * @param string $stylesheet Name of the stylesheet file
     * @param string $extension Extension of the stylesheet file
     * @param bool $xhtml Whether or not to use XHTML for rendering
     *
     * @return string
     * @throws \Chromabits\Illuminated\Inliner\Exceptions\StylesheetNotFoundException
     * @throws \TijsVerkoyen\CssToInlineStyles\Exception
     */
    public function inline($content, $stylesheet, $extension = '.css', $xhtml = false);
}
