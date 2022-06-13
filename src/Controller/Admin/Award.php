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
use award\View\AwardView;
use award\Factory\AwardFactory;
use Canopy\Request;

class Award extends AbstractController
{

    /**
     * Returns form for award creation.
     * @return string
     */
    protected function createHtml()
    {
        $award = AwardFactory::build();
        return AwardView::editForm($award);
    }

    protected function deleteHtml()
    {
        $this->idRequired();
        $award = AwardFactory::build($this->id);
        return AwardView::deleteForm($award);
    }

    protected function delete(Request $request)
    {
        $this->idRequired();
        AwardFactory::delete($this->id);
        return ['success' => true];
    }

    protected function editHtml()
    {
        $this->idRequired();
        $award = AwardFactory::build($this->id);
        return AwardView::editForm($award);
    }

    protected function hasCyclesJson(Request $request)
    {
        return ['hasCycles' => AwardFactory::hasCycles($this->id)];
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
        return AwardFactory::getList();
    }

    protected function newAwardHtml()
    {
        return AwardView::newAward(AwardFactory::build($this->id));
    }

    protected function post(Request $request)
    {
        $award = AwardFactory::post($request);
        $award = AwardFactory::save($award);

        return ['success' => true, 'id' => $award->getId()];
    }

    protected function put(Request $request)
    {
        $this->idRequired();
        $award = AwardFactory::put($request, $this->id);
        $award = AwardFactory::save($award);

        return ['success' => true, 'id' => $award->getId()];
    }

    /**
     * Retrieves a listing of award id and titles only.
     *
     * @param Request $request
     */
    protected function basicJson(Request $request)
    {
        return AwardFactory::getList(['basic' => true, 'orderBy' => 'title', 'orderDir' => 'asc']);
    }

}
