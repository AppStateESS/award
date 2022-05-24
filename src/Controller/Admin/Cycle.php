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

class Cycle extends AbstractController
{

    /**
     * Returns form for a cycle based on an award.
     * @return string
     */
    protected function createHtml(Request $request)
    {
        $awardId = $request->pullGetInteger('awardId');
        $award = AwardFactory::build($awardId);
        $cycle = CycleFactory::build();
        $cycle->setAwardId($awardId);
        $cycle->setTerm($award->getCycleTerm());
        $cycle->setVoteType($award->getDefaultVoteType());
        return CycleView::editForm($cycle);
    }

    protected function editHtml()
    {
        $cycle = CycleFactory::build($this->id);
        var_dump($cycle);
        exit;

        return CycleView::editForm($cycle);
    }

    protected function listHtml(Request $request)
    {
        return CycleView::adminList((int) $request->pullGetInteger('awardId', true));
    }

    protected function listJson(Request $request)
    {
        $options = [];
        $awardId = $request->pullGetInteger('awardId', true);
        if ($awardId) {
            $options['awardId'] = $awardId;
        }
        return CycleFactory::list($options);
    }

    protected function post(Request $request)
    {
        $cycle = CycleFactory::post($request);
        $cycle = CycleFactory::save($cycle);
        AwardFactory::setCurrentCycle($cycle);

        return ['success' => true, 'id' => $cycle->getId()];
    }

    protected function put(Request $request)
    {
        $cycle = CycleFactory::put($request);
        $cycle = CycleFactory::save($cycle);

        return ['success' => true, 'id' => $cycle->getId()];
    }

}
