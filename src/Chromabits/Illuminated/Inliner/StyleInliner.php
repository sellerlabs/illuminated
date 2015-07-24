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

use Chromabits\Illuminated\Contracts\Inliner\StyleInliner as InlinerContract;
use Chromabits\Illuminated\Inliner\Exceptions\StylesheetNotFoundException;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\View\View;
use Illuminate\Mail\Message;
use Symfony\Component\Finder\Finder;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

/**
 * Class StyleInliner.
 *
 * Converts CSS/HTML file combinations into a single string
 * of HTML with inlined styles, which is useful for rendering the
 * contents of an email since many services strip style blocks
 * from emails.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Inliner
 */
class StyleInliner implements InlinerContract
{
    /**
     * Internal inliner.
     *
     * @var CssToInlineStyles
     */
    protected $internalInliner;

    /**
     * Options.
     *
     * @var array
     */
    protected $options;

    /**
     * Array of directories where we can locate stylesheets.
     *
     * @var array
     */
    protected $stylesheetPaths;

    /**
     * Construct an instance of a StyleInliner.
     *
     * @param array $stylesheets
     * @param array $options
     */
    public function __construct(array $stylesheets, array $options = null)
    {
        $this->stylesheetPaths = $stylesheets;
        $this->options = $options;

        if (is_null($this->options)) {
            $this->options = [
                'cleanup' => false,
                'use_inline_styles_block' => false,
                'strip_original_tags' => false,
                'exclude_media_queries' => false,
            ];
        }

        $this->internalInliner = new CssToInlineStyles();

        $this->configure();
    }

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
    ) {
        // If the content is a Laravel view, then we will render it first
        if ($content instanceof View) {
            $content = $content->render();
        }

        // Set the HTML content of the inliner
        $this->internalInliner->setHTML($content);

        // Resolve the stylesheet and set it as the CSS of the inliner
        $this->internalInliner->setCSS(
            $this->resolveStylesheet($stylesheet, $extension)->getContents()
        );

        // Inline the styles into style blocks
        $result = $this->internalInliner->convert($xhtml);

        // Do some housekeeping before returning
        $this->cleanup();

        return $result;
    }

    /**
     * Attempt to resolve the file path of a stylesheet using its name.
     *
     * @param string $name
     * @param string $extension
     *
     * @throws StylesheetNotFoundException
     * @return mixed
     */
    protected function resolveStylesheet($name, $extension = '.css')
    {
        $finder = new Finder();

        $files = $finder
            ->files()
            ->in($this->stylesheetPaths)
            ->name($name . $extension);

        if (iterator_count($files) < 1) {
            throw new StylesheetNotFoundException();
        }

        $iterator = $files->getIterator();
        $iterator->next();

        return $iterator->current();
    }

    /**
     * Reset the internal inliner object.
     */
    protected function cleanup()
    {
        $this->internalInliner->setHTML('');
        $this->internalInliner->setCSS('');
    }

    /**
     * Configure the internal inliner.
     */
    protected function configure()
    {
        $this->internalInliner->setCleanup($this->options['cleanup']);

        $this->internalInliner->setUseInlineStylesBlock(
            $this->options['use_inline_styles_block']
        );
        $this->internalInliner->setStripOriginalStyleTags(
            $this->options['strip_original_tags']
        );
        $this->internalInliner->setExcludeMediaQueries(
            $this->options['exclude_media_queries']
        );
    }

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
        $content, $name,
        callable $callback
    ) {
        $content = $this->inline($content, $name);

        $mailer->send(
            ['raw' => ''],
            [],
            function (Message $message) use ($content, $callback) {
                $message->getSwiftMessage()->setBody($content, 'text/html');

                $callback($message);
            }
        );
    }
}
