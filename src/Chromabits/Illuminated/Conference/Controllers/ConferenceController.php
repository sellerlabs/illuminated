<?php

namespace Chromabits\Illuminated\Conference\Controllers;

use Chromabits\Illuminated\Conference\Entities\ConferenceContext;
use Chromabits\Illuminated\Conference\Entities\SidebarPanelPair;
use Chromabits\Illuminated\Conference\Interfaces\DashboardInterface;
use Chromabits\Illuminated\Conference\Views\ConferencePage;
use Chromabits\Illuminated\Http\BaseController;
use Illuminate\Http\Request;

/**
 * Class ConferenceController
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\Illuminated\Conference\Controllers
 */
class ConferenceController extends BaseController
{
    /**
     * @var DashboardInterface
     */
    protected $dashboard;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ConferenceContext
     */
    protected $context;

    /**
     * Construct an instance of a ConferenceController.
     *
     * @param DashboardInterface $dashboard
     * @param Request $request
     * @param ConferenceContext $context
     */
    public function __construct(
        DashboardInterface $dashboard,
        Request $request,
        ConferenceContext $context
    ) {
        $this->dashboard = $dashboard;
        $this->request = $request;
        $this->context = $context;
    }

    public function anyIndex() {
        return $this->renderDashboard(
            $this->dashboard->run($this->request, $this->context)
        );
    }

    public function anyModule($moduleName) {
        return $this->renderDashboard(
            $this->dashboard->run($this->request, $this->context, $moduleName)
        );
    }

    public function anyMethod($moduleName, $methodName) {
        return $this->renderDashboard(
            $this->dashboard->run(
                $this->request,
                $this->context,
                $moduleName,
                $methodName
            )
        );
    }

    protected function renderDashboard(SidebarPanelPair $result)
    {
        if ($result->hasSidebar()) {
            return (new ConferencePage(
                $this->context,
                $result->getPanel(),
                $result->getSidebar()
            ))->render();
        }

        return (new ConferencePage(
            $this->context,
            $result->getPanel()
        ))->render();
    }
}