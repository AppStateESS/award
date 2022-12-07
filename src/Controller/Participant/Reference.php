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
use award\View\ReferenceView;
use award\View\ParticipantView;
use award\Factory\EmailFactory;
use award\Factory\ParticipantFactory;
use award\Factory\ReferenceFactory;
use award\Factory\Authenticate;
use award\Exception\ParticipantPrivilegeMissing;

class Reference extends AbstractController
{

    protected function listJson(Request $request)
    {
        $options = [];
        $options['nominationId'] = $nominationId = $request->pullGetInteger('nominationId');
        if (ParticipantFactory::currentOwnsNomination($nominationId)) {
            throw new ParticipantPrivilegeMissing;
        }
        $options['includeParticipant'] = true;
        return ReferenceFactory::listing($options);
    }

    protected function reasonHtml()
    {
        $reference = ReferenceFactory::build($this->id);
        $participant = ParticipantFactory::getCurrentParticipant();
        if (!ParticipantFactory::currentIsReference($reference->id)) {
            throw new ParticipantPrivilegeMissing();
        }
        if (!ReferenceFactory::canUpdate($reference)) {
            return ReferenceView::passedUpdate();
        }
        return ParticipantView::participantMenu('dashboard') . ReferenceView::reasonForm($reference, $participant);
    }

    protected function remindHtml(Request $request)
    {
        return ParticipantView::participantMenu('nomination') . '<p>Send reminder is incomplete.</p>';
    }

    protected function remindJson()
    {
        $reference = ReferenceFactory::build($this->id);
        $participant = ParticipantFactory::getCurrentParticipant();

        if (!ParticipantFactory::currentOwnsReference($reference->id)) {
            throw new ParticipantPrivilegeMissing();
        }

        EmailFactory::referenceReminder($reference, $participant);
        $reference->stampLastReminder();
        ReferenceFactory::save($reference);
        return ['success' => true];
    }

    protected function textPut(Request $request)
    {
        $reference = ReferenceFactory::build($this->id);
        if (!ParticipantFactory::currentOwnsReference($reference->id)) {
            throw new ParticipantPrivilegeMissing();
        }
        $reference->setReasonText($request->pullPutString('reasonText'));
        ReferenceFactory::save($reference);
        return ['success' => true];
    }

    protected function uploadPost(Request $request)
    {
        $reference = ReferenceFactory::build($request->pullPostInteger('referenceId'));

        if (!ParticipantFactory::currentIsReference($reference->id)) {
            throw new ParticipantPrivilegeMissing();
        }
        if (empty($_FILES['document'])) {
            return ['success' => false, 'error' => 'document file not found'];
        }
        $fileArray = $_FILES['document'];
        return ReferenceFactory::saveDocument($reference, $fileArray);
    }

}
