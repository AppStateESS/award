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
use award\Factory\AwardFactory;
use award\Factory\CycleFactory;
use award\Factory\DocumentFactory;
use award\Factory\NominationFactory;
use award\Factory\ParticipantFactory;
use award\Factory\ReasonFactory;
use award\Factory\ReferenceFactory;
use award\Exception\ResourceNotFound;
use award\Exception\ParticipantPrivilegeMissing;
use award\View\ReasonView;

class Reason extends AbstractController
{

    protected function createReferenceHtml(Request $request)
    {
        $referenceId = $request->pullGetInteger('referenceId');
        if (!ParticipantFactory::currentIsReference($referenceId)) {
            throw new ParticipantPrivilegeMissing();
        }

        if (!$referenceId) {
            throw new \Exception('missing reference id');
        }
        $reason = ReasonFactory::getByReferenceId($referenceId);
        $reference = ReferenceFactory::build($referenceId);
        $nomination = NominationFactory::build($reference->getNominationId());
        if (!$reason) {
            $reason = ReasonFactory::build();
            $reason->setReferenceId($reference->getId());
            $reason->setNominationId($nomination->getId());
            $reason->setReasonType(AWARD_REASON_REFERENCE);
            $reason->setCycleId($nomination->getCycleId());
        }
        $cycle = CycleFactory::build($reason->getCycleId());
        $award = AwardFactory::getByCycleId($cycle->getId());

        if (!$award->getReferenceReasonRequired()) {
            return self::referenceReasonNotRequired();
        }

        return ReasonView::referenceForm($reason, $reference, $nomination, $award, $cycle);
    }

    protected function createNominationHtml(Request $request)
    {
        $nominationId = $request->pullGetInteger('nominationId');
        if (!$nominationId) {
            throw new \Exception('missing nomination id');
        }
        $reason = ReasonFactory::getByReferenceId($referenceId);
        if (!$reason) {
            $nomination = NominationFactory::build($nominationId);
            $reason = ReasonFactory::build();
            $reason->setNominationId($nominationId);
            $reason->setReasonType(AWARD_REASON_NOMINATION);
            $reason->setCycleId($nomination->getCycleId());
        }
        return ReasonView::nominationForm($reason, $nomination);
    }

    protected function editHtml()
    {
        $reason = ReasonFactory::build($this->id);
        if (!$reason) {
            throw new ResourceNotFound();
        }
        $nomination = NominationFactory::build($reason->getNominationId());
        $award = AwardFactory::build($nomination->getAwardId());
        $cycle = CycleFactory::build($nomination->getCycleId());
        if ($reason->isReference()) {
            if (!ParticipantFactory::currentIsReference($reason->getReferenceId())) {
                throw new ParticipantPrivilegeMissing();
            }
            $reference = ReferenceFactory::build($reason->getReferenceId());
            return ReasonView::referenceForm($reason, $nomination, $award, $cycle);
        } elseif ($reason->isNomination()) {
            if (!ParticipantFactory::currentOwnsNomination($reason->getNominationId())) {
                throw new ParticipantPrivilegeMissing();
            }
            return ReasonForm::nominationForm($reason, $nomination, $award, $cycle);
        } else {
            throw new ResourceNotFound();
        }
    }

    protected function post(Request $request)
    {
        $reason = ReasonFactory::postBasic($request);
        $reason->setReasonText($request->pullPostString('reasonText'));
        ReasonFactory::save($reason);
        ReasonFactory::linkReason($reason);
        return ['success' => true, 'id' => $reason->getId()];
    }

    protected function put(Request $request)
    {
        $reason = ReasonFactory::build($this->id);
        $reason->setReasonText($request->pullPutString('reasonText'));
        ReasonFactory::save($reason);
        return ['success' => true, 'id' => $reason->getId()];
    }

    /**
     * A XHR command. Removes a document from a reason.
     * If the reason is incomplete (no text), then it is deleted.
     * An empty reason cannot exist.
     * @return array
     * @throws ParticipantPrivilegeMissing
     */
    protected function removeDocumentDelete()
    {
        $reason = ReasonFactory::build($this->id);
        if (!ReasonFactory::allowed($reason)) {
            throw new ParticipantPrivilegeMissing();
        }

        if ($reason->getDocumentId()) {
            ReasonFactory::clearCurrentDocument($reason);
        }

        if ($reason->isComplete()) {
            ReasonFactory::save($reason);
            return ['success' => true, 'reasonDeleted' => false];
        } else {
            ReasonFactory::unlinkReason($reason);
            ReasonFactory::delete($reason);
            return ['success' => true, 'reasonDeleted' => true];
        }
    }

    // Reference only
    protected function uploadPost(Request $request)
    {
        if (empty($_FILES['document'])) {
            return ['success' => false, 'error' => 'document file not found'];
        }
        $fileArray = $_FILES['document'];

        $reasonId = $request->pullPostInteger('reasonId', true);
        $newReason = true;
        if ($reasonId) {
            $newReason = false;
            $reason = ReasonFactory::build($reasonId);
        } else {
            $reason = ReasonFactory::postBasic($request);
            ReasonFactory::save($reason);
        }

        $document = DocumentFactory::createDocument($fileArray, $reason);
        ReasonFactory::clearCurrentDocument($reason);
        $reason->setDocumentId($document->getId());
        ReasonFactory::save($reason);
        if ($newReason) {
            ReasonFactory::linkReason($reason);
        }
        return ['success' => true, 'documentId' => $document->getId(),
            'filename' => $document->getTitle()];
    }

}
