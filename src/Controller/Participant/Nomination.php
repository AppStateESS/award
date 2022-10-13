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

    /**
     * View for the selection or creation of a participant.
     * @param Request $request
     * @return type
     * @throws ResourceNotFound
     */
    protected function nominateHtml(Request $request)
    {
        $cycleId = $request->pullGetInteger('cycleId');
        if (empty($cycle = CycleFactory::build($cycleId))) {
            throw new ResourceNotFound;
        }
        if (empty($award = AwardFactory::build($cycle->awardId))) {
            throw new ResourceNotFound;
        }

        $nominator = ParticipantFactory::getCurrentParticipant();
        $nomination = NominationFactory::getByNominator($nominator->id, $cycleId);

        /**
         * If the nomination for this cycle is already started, send them to the
         * status page.
         */
        if ($nomination) {
            // Although the nomination was started, we don't allow them to continue
            // if the cycle status changes.
            $nominated = ParticipantFactory::build($nomination->participantId);
            try {
                CycleFactory::nominationAllowed($cycle);
                ParticipantFactory::canNominate($nominator, $nominated, $cycle->id);
            } catch (\Exception $ex) {
                return ParticipantView::participantMenu('nomination') .
                    NominationView::errorByException($ex, $award, $cycle);
            }
            return NominationView::nominationStatus($nominator, $nomination);
        }





        /**
         * If nominator has already started a nomination, continue with it.
         */
        if ($nomination) {
            $participant = ParticipantFactory::build($nomination->participantId);
            return ParticipantView::participantMenu('nomination') . NominationView::nominateParticipant($nominator, $nomination, $participant, $award, $cycle);
        } else {
            return ParticipantView::participantMenu('nomination') . NominationView::nominate($award, $cycle);
        }
    }

    protected function nominateStatus()
    {

    }

    protected function nominateParticipantHtml(Request $request)
    {
        if (!ParticipantFactory::currentIsTrusted()) {
            return NominationView::onlyTrusted();
        }
        if ($this->id) {
            $nomination = NominationFactory::build($this->id);
            $participantId = $nomination->participantId;
            $cycleId = $nomination->cycleId;
        } else {
            $participantId = $request->pullGetInteger('participantId');
            $cycleId = $request->pullGetInteger('cycleId');
        }

        $nominator = ParticipantFactory::getCurrentParticipant();
        $participant = ParticipantFactory::build($participantId);
        $cycle = CycleFactory::build($cycleId);
        $award = AwardFactory::build($cycle->awardId);

        $nomination = NominationFactory::getByNominator($nominator->id, $cycle->id);
        if ($nomination === false) {
            if ($participant->getBanned() || !$participant->getActive()) {
                return ParticipantView::participantMenu('nomination') . ParticipantView::inaccessible();
            }
            // If there is not an existing nomination, create one if the participant
            $nomination = \award\Factory\NominationFactory::create($nominator, $participant->id, $award->id, $cycle->id);
        }

        try {
            NominationFactory::errorCheckNomination($cycle, $award, $nominator, $nomination);
        } catch (\Exception $ex) {
            return ParticipantView::participantMenu('nomination') .
                NominationView::errorByException($ex, $award, $cycle);
        }


        return NominationView::nominateParticipant($nominator, $nomination, $participant, $award, $cycle);
    }

    protected function reasonHtml(Request $request)
    {
        if (!ParticipantFactory::currentIsTrusted()) {
            return NominationView::onlyTrusted();
        }
        $nominator = ParticipantFactory::getCurrentParticipant();

        $nomination = NominationFactory::build($this->id);
        $award = AwardFactory::build($nomination->awardId);
        $cycle = CycleFactory::build($nomination->cycleId);
        $nominee = ParticipantFactory::build($nomination->participantId);
        return NominationView::reasonForm($award, $cycle, $nomination, $nominee);
    }

    /**
     * Lets nominator choose references for a nomination.
     * @param Request $request
     */
    protected function referenceHtml(Request $request)
    {
        $nomination = NominationFactory::build($this->id);
    }

    protected function textPut(Request $request)
    {
        $nomination = NominationFactory::build($this->id);
        $reasonText = $request->pullPutString('reasonText');
        if (empty($reasonText)) {
            return ['success' => false, 'message' => 'reason text is empty'];
        }
        $nomination->setReasonText($reasonText)->setReasonComplete(true);
        NominationFactory::save($nomination);

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
