<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Contracts\Inliner;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\View\View;
use SellerLabs\Illuminated\Inliner\Exceptions\StylesheetNotFoundException;

/**
 * Interface StyleInliner.
 *
 * Converts CSS/HTML file combinations into a single string
 * of HTML with inlined styles, which is useful for rendering the
 * contents of an email since many services strip style blocks
 * from emails.
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Contracts\Inliner
 */
interface StyleInliner
{
    /**
     * Inline CSS stylesheet into a HTML string or Laravel view.
     *
     * If a Laravel view is provided, the view will be rendered
     *
     * @param string|View $content
     * @param string $stylesheet Name of the stylesheet file
     * @param string $extension Extension of the stylesheet file
     * @param bool $xhtml Whether or not to use XHTML for rendering
     *
     * @throws StylesheetNotFoundException
     * @throws \TijsVerkoyen\CssToInlineStyles\Exception
     * @return string
     */
    public function inline(
        $content,
        $stylesheet,
        $extension = '.css',
        $xhtml = false
    );

    /**
     * Inline the content and then send it over the mailer.
     *
     * @param Mailer $mailer
     * @param mixed $content
     * @param string $name
     * @param callable $callback
     */
    public function inlineAndSend(
        Mailer $mailer,
        $content,
        $name,
        callable $callback
    );
}
