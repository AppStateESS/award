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
use award\View\ParticipantView;
use award\Exception\ResourceNotFound;
use award\Factory\ParticipantFactory;
use award\Factory\InvitationFactory;
use award\Factory\EmailFactory;
use award\Factory\AwardFactory;
use award\Factory\CycleFactory;
use award\Factory\NominationFactory;

class Nomination extends AbstractController
{

    // TODO finish
    protected function listHtml()
    {
        return NominationView::participantView();
    }

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
            return ParticipantView::participantMenu('nomination') . CycleView::complete();
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
        return ParticipantView::participantMenu('nomination') . NominationView::nominate($award, $cycle);
    }

    protected function nominateParticipantHtml(Request $request)
    {
        if (!ParticipantFactory::currentIsTrusted()) {
            return NominationView::onlyTrusted();
        }
        $participantId = $request->pullGetInteger('participantId');
        $cycleId = $request->pullGetInteger('cycleId');

        $nominator = ParticipantFactory::getCurrentParticipant();
        $participant = ParticipantFactory::build($participantId);
        $cycle = CycleFactory::build($cycleId);
        $award = AwardFactory::build($cycle->awardId);

        if (ParticipantFactory::currentIsJudge($cycleId)) {
            return ParticipantView::participantMenu('nomination') . NominationView::noJudges($award, $cycle);
        }
        if ($participant->getBanned() || !$participant->getActive()) {
            return ParticipantView::participantMenu('nomination') . ParticipantView::inaccessible();
        }

        return NominationView::nominateParticipant($nominator, $participant, $award, $cycle);
    }

    protected function textPost(Request $request)
    {
        $participantId = $request->pullPostInteger('participantId');
        $cycleId = $request->pullPostInteger('cycleId');
        $reasonText = $request->pullPostString('reasonText');
        NominationFactory::nominateParticipantText($participantId, $cycleId, $reasonText);
        return ['success' => true];
    }

    protected function uploadPost(Request $request)
    {
        if (empty($_FILES['nominationUpload'])) {
            throw new \Exception('nomination document not found');
        }

        $document = DocumentFactory::build();

        exit;
    }

}
