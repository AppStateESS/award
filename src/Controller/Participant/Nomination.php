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

    protected function viewHtml(Request $request)
    {
        $nominator = ParticipantFactory::getCurrentParticipant();
        $nomination = NominationFactory::build($this->id);
        $nominated = ParticipantFactory::build($nomination->participantId);

        $cycle = CycleFactory::build($nomination->cycleId);
        $award = AwardFactory::build($nomination->awardId);

        try {
            CycleFactory::nominationAllowed($cycle);
            ParticipantFactory::canNominate($nominator, $nominated, $cycle->id);
        } catch (\Exception $ex) {
            return ParticipantView::participantMenu('nomination') .
                NominationView::errorByException($ex, $award, $cycle);
        }
        return NominationView::participantMenu('nomination') . NominationView::view($nominator, $nomination);
    }

    /**
     * View for the selection or creation of a participant.
     * @param Request $request
     * @return string
     * @throws ResourceNotFound
     */
    protected function nominateHtml(Request $request)
    {
        $nominator = ParticipantFactory::getCurrentParticipant();

        $cycleId = $request->pullGetInteger('cycleId');

        if (empty($cycle = CycleFactory::build($cycleId))) {
            throw new ResourceNotFound;
        }
        if (empty($award = AwardFactory::build($cycle->awardId))) {
            throw new ResourceNotFound;
        }

        return ParticipantView::participantMenu('nomination') . NominationView::nominate($award, $cycle);
    }

    /**
     * Posts a new nomination. Skips the save if the nomination already exists.
     * @param Request $request
     * @return type
     */
    protected function post(Request $request)
    {
        $nominator = ParticipantFactory::getCurrentParticipant();
        $participantId = $request->pullPostInteger('participantId');
        $cycle = CycleFactory::build($request->pullPostInteger('cycleId'));

        if (empty($nomination = NominationFactory::getByNominator($nominator->id, $participantId, $cycle->id))) {
            $nomination = NominationFactory::create($nominator->id, $participantId, $cycle->awardId, $cycle->id);
        }

        try {
            CycleFactory::nominationAllowed($cycle);
            NominationFactory::nominationAllowed($nominator, $nomination);
            return ['success' => true, 'id' => $nomination->id];
        } catch (Exception $ex) {
            return ['success' => false, 'message' => $ex->getMessage()];
        }
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
