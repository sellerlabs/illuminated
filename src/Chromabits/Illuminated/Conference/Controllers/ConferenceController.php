<?php

namespace Chromabits\Illuminated\Conference\Controllers;

use Chromabits\Illuminated\Conference\Entities\ConferenceContext;
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
    public function anyIndex(
        DashboardInterface $dashboard,
        Request $request,
        ConferenceContext $context
    ) {
        $result = $dashboard->run($request, $context);

        if ($result->hasSidebar()) {
            return (new ConferencePage(
                $result->getPanel(),
                $result->getSidebar()
            ))->render();
        }

        return (new ConferencePage($result->getPanel()))->render();
    }
}