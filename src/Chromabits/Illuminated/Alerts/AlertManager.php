<?php

namespace Chromabits\Illuminated\Alerts;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Session\Store;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Chromabits\Illuminated\Contracts\Alerts\AlertManager as ManagerContract;

/**
 * Class AlertManager
 *
 * A service for handling the display of alerts through an application
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Alerts
 */
class AlertManager implements ManagerContract
{
    /**
     * Session
     *
     * A reference to the session store of the application
     *
     * @var Store
     */
    protected $session;

    /**
     * Top-level alert view
     *
     * This View object will be used to render each alert, a data context
     * will be provided to the view with the alert's content, type, and title.
     *
     * @var View
     */
    protected $view;

    /**
     * Construct an instance of an AlertManager
     *
     * @param \Illuminate\Session\Store $session
     * @param \Illuminate\View\View $view
     */
    public function __construct(Store $session, View $view)
    {
        $this->session = $session;

        $this->view = $view;
    }

    /**
     * Creates and adds a new alert into the store
     *
     * This is factory function that will create a new Alert object and
     * add it to the session collection so that it may be accessed later
     *
     * @param $content
     * @param string $type
     * @param string|null $title
     * @param string|null $view
     */
    public function push(
        $content,
        $type = Alert::TYPE_NEUTRAL,
        $title = null,
        $view = null
    ) {
        $alert = new Alert();

        $alert->setContent($content);
        $alert->setType($type);
        $alert->setTitle($title);
        $alert->setView($view);

        $collection = $this->prepareAndGet();

        $collection->push($alert);
    }

    /**
     * Prepare and get the alert collection
     *
     * Here we make sure that the alerts collection is in the session
     * store. If it's not, then we will go ahead an create a new one
     *
     * @return Collection
     */
    protected function prepareAndGet()
    {
        if (!$this->session->has('chromabits.alerts')) {
            $this->session->set('chromabits.alerts', new Collection());
        }

        return $this->session->get('chromabits.alerts');
    }

    /**
     * Creates an alert from the result of validator
     *
     * The type of the alert will be TYPE_VALIDATION
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @param null $title
     * @param null $view
     */
    public function pushValidation(
        Validator $validator,
        $title = null,
        $view = null
    ) {
        $messages = $validator->getMessageBag()->all();

        $this->push($messages, Alert::TYPE_VALIDATION, $title, $view);
    }

    /**
     * Take a look at the oldest alert in the collection
     *
     * @return Alert|null
     */
    public function peek()
    {
        return $this->prepareAndGet()->first();
    }

    /**
     * Get all the alerts currently in the store
     *
     * @return Alert[]
     */
    public function peekAll()
    {
        return $this->prepareAndGet()->all();
    }

    /**
     * Take the oldest alert out of the collection and render it
     *
     * @return null|string
     */
    public function takeAndRender()
    {
        $alert = $this->take();

        if (is_null($alert)) {
            return null;
        }

        return $alert->render($this->view);
    }

    /**
     * Take the oldest alert out of the collection
     *
     * @return Alert|null
     */
    public function take()
    {
        $slice = $this->prepareAndGet()->take(1);

        if (count($slice) < 1) {
            return null;
        }

        return $slice[0];
    }

    /**
     * Clear the collection and return all the alerts
     *
     * @return array
     */
    public function all()
    {
        $all = $this->prepareAndGet()->all();

        $this->session->set('chromabits.alerts', new Collection());

        return $all;
    }

    /**
     * Render all the alerts and then clear the collection
     *
     * @return array
     */
    public function allAndRender()
    {
        $all = $this->all();

        return array_reduce($all, function ($carry, $alert) {
            $carry .= $alert->render($this->view);

            return $carry;
        });
    }
}
