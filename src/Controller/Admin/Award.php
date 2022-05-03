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

use award\Controller\AbstractController;
use award\View\DashboardView;
use award\View\AwardView;
use award\Factory\AwardFactory;
use Canopy\Request;

class Award extends AbstractController
{

    protected function createHtml()
    {
        $award = AwardFactory::build();
        return AwardView::editForm($award);
    }

    /**
     * HTML listing of Awards seen by the admin.
     * @return type
     */
    protected function listHtml()
    {
        return AwardView::adminList();
    }

    /**
     * A listing of the current awards
     * @return array
     */
    protected function listJson()
    {
        return [];
    }

    protected function post(Request $request)
    {
        $request->pullPostVars();
        $award = AwardFactory::post($request);
        $award = AwardFactory::save($award);
        return ['success' => true, 'id' => $award->getId()];
    }

}
