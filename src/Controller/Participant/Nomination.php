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

namespace award\Controller\Participant;

use Canopy\Request;
use award\AbstractClass\AbstractController;
use award\View\NominationView;
use award\Exception\ResourceNotFound;
use award\Factory\ParticipantFactory;
use award\Factory\InvitationFactory;
use award\Factory\EmailFactory;
use award\Factory\AwardFactory;
use award\Factory\CycleFactory;

class Nomination extends AbstractController
{

    protected function nominateHtml(Request $request)
    {
        if (!ParticipantFactory::currentIsTrusted()) {
            return NominationView::onlyTrusted();
        }
        $cycleId = $request->pullGetInteger('cycleId');
        $cycle = CycleFactory::build($cycleId);

        if (empty($cycle) || $cycle->deleted) {
            throw new ResourceNotFound;
        }

        if ($cycle->completed) {
            return CycleView::complete();
        }

        $award = AwardFactory::build($cycle->awardId);

        if (ParticipantFactory::currentIsJudge($cycleId)) {
            return NominationView::noJudges($award, $cycle);
        }

        if ($cycle->endDate < time()) {
            return NominationView::deadlinePassed($award, $cycle);
        }

        if (empty($award)) {
            throw new ResourceNotFound;
        }
        return NominationView::nominate($award, $cycle);
    }

}
