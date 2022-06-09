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

namespace award\Controller\Admin;

use award\AbstractClass\AbstractController;
use award\View\DashboardView;
use award\View\CycleView;
use award\Factory\CycleFactory;
use award\Factory\AwardFactory;
use Canopy\Request;

class Setting extends AbstractController
{

    public function listHtml()
    {
        return 'hi';
    }

}
