<?php

declare(strict_types=1);

/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * This class is the controller for authenticated Participants.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\Controller\Participant;

use Canopy\Request;
use award\AbstractClass\AbstractController;
use award\View\ParticipantView;
use award\Factory\Authenticate;
use award\Factory\EmailFactory;
use award\Factory\InvitationFactory;
use award\Factory\JudgeFactory;
use award\Factory\NominationFactory;
use award\Factory\ParticipantFactory;
use award\Factory\ReferenceFactory;

class Participant extends AbstractController
{

    protected function dashboardHtml()
    {
        return ParticipantView::dashboard();
    }

    /**
     * Returns an array of available references. Current references, judges from the cycle, and the
     * current participant are not allowed.
     * @param Request $request
     * @return type
     */
    protected function referenceAvailableJson(Request $request)
    {
        $participant = ParticipantFactory::getCurrentParticipant();

        $nominationId = $request->pullGetInteger('nominationId');
        $cycleId = $request->pullGetInteger('cycleId');

        $nomination = NominationFactory::build($nominationId);

        /**
         * Do not return references that are already a reference for this nomination.
         */
        $references = ReferenceFactory::listing(['nominationId' => $nominationId, 'participantIdOnly' => true]);
        /**
         * Do not return judges who are assigned to this cycle.
         */
        $judges = JudgeFactory::listing(['cycleId' => $cycleId, 'participantIdOnly' => true]);

        $invitedIds = InvitationFactory::listing(['cycleId' => $cycleId, 'senderId' => $participant->id, 'invitedIdOnly' => true]);

        $notIn = array_merge($references, $judges, $invitedIds);
        /**
         * Do not return the current participant looking for references
         */
        $notIn[] = $participant->id;
        /**
         * Do not return the participant nominated as a possible reference.
         */
        $notIn[] = $nomination->nominatedId;

        $options['notIn'] = $notIn;
        $options['asSelect'] = true;
        $options['search'] = $request->pullGetString('search');
        return ParticipantFactory::listing($options);
    }

    protected function searchNomineesJson(Request $request)
    {
        if (!ParticipantFactory::currentIsTrusted()) {
            return ['error' => 'untrusted'];
        }
        $options['search'] = $request->pullGetString('search');
        $cycleId = $request->pullGetInteger('cycleId');
        $options['cycleId'] = $cycleId;

        $notIn = NominationFactory::listing(['nominatedIdOnly' => true, 'cycleId' => $cycleId]);
        $options['notIn'] = $notIn;
        return ParticipantFactory::listing($options);
    }

    protected function signoutHtml()
    {
        $participant = ParticipantFactory::getCurrentParticipant();

        \award\Factory\ParticipantFactory::signOut();
        \award\Factory\AuthenticateFactory::signOut($participant->authType);
        return ParticipantView::signedOut();
    }

}
