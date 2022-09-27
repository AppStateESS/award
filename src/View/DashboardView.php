<?php

declare(strict_types=1);
/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\View;

use award\AbstractClass\AbstractView;

class DashboardView extends AbstractView
{

    public static function admin()
    {
        $params['menu'] = self::adminMenu('dashboard');
        $params['upcomingCycles'] = CycleView::upcoming();

        return self::getTemplate('Admin/Dashboard', $params, true);
    }

    private static function newAwardRequests()
    {
        AwardView::newAwardRequests();
    }

}
