<?php

/**
 * Copyright 2016, Seller Labs <engineering@sellerlabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This file is part of the Illuminated package
 */

namespace SellerLabs\Illuminated\Alerts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\View\View;

/**
 * Class Alert.
 *
 * Represents an alert message
 *
 * @author Eduardo Trujillo <ed@roundsphere.com>
 * @package SellerLabs\Illuminated\Alerts
 */
class Alert implements Arrayable
{
    const TYPE_INFO = 'info';
    const TYPE_ERROR = 'error';
    const TYPE_WARNING = 'warning';
    const TYPE_SUCCESS = 'success';
    const TYPE_NEUTRAL = 'neutral';
    const TYPE_VALIDATION = 'validation';

    /**
     * Title.
     *
     * Usually displayed before the content, and describing
     * the nature of the alert
     *
     * @var string|null
     */
    protected $title;

    /**
     * Content.
     *
     * For the top-level alert view, this field will be made available
     * along others as part of that view's data context.
     *
     * Note: Content can also be pre-rendered by an internal message view
     *
     * @see view
     *
     * @var string|array|null
     */
    protected $content;

    /**
     * Internal view name.
     *
     * An internal view can pre-render the content variable before it is
     * passed onto the top-level alert-view. This useful for having
     * one generic view for alerts and then more specific content views
     * which depend on the kind of alert.
     *
     * One specific use case where this might happen is for showing an
     * form validation error. These kind of error alerts can include
     * multiple error message per field, so it is useful to render
     * them in a ordered list instead of just showing the plain text
     *
     * @var string|null
     */
    protected $view;

    /**
     * Type.
     *
     * A simple string describing the nature of the alert message. Views
     * can use this field to alter the color or icons of the alert
     * according to the severity of the alert.
     *
     * Some predefined types are included in this class as constants
     *
     * @var string
     */
    protected $type;

    /**
     * Construct an instance of an Alert.
     */
    public function __construct()
    {
        // Here we initialize all internal variables to safe defaults
        $this->title = null;
        $this->content = null;
        $this->view = null;
        $this->type = self::TYPE_NEUTRAL;
    }

    /**
     * Get the title of the alert.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set or clear the title of the alert.
     *
     * @param string|null $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get the content of the alert.
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the content of the alert.
     *
     * @param string|null $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get the type of the alert.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the type of the alert.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Set an optional view to pre-render the content field.
     *
     * @see view
     *
     * @param string $name
     */
    public function setView($name)
    {
        $this->view = $name;
    }

    /**
     * Render the alert.
     *
     * @param View $view
     *
     * @return string
     */
    public function render(View $view)
    {
        return $view->with($this->toArray())->render();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->renderContent(),
            'type' => $this->type,
            'internal_view' => $this->view,
        ];
    }

    /**
     * Pre-process the content field.
     *
     * If the alert has an internal view, it will use it to render the
     * content property.
     *
     * @return array|null|string
     */
    protected function renderContent()
    {
        if (!is_null($this->view) || $this->view != '') {
            return view(
                $this->view,
                [
                    'title' => $this->title,
                    'content' => $this->content,
                    'type' => $this->type,
                ]
            )->render();
        }

        return $this->content;
    }
}
