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
use award\View\ParticipantView;
use award\Factory\InvitationFactory;
use award\Factory\JudgeFactory;
use award\Factory\NominationFactory;
use award\Factory\ParticipantFactory;
use Canopy\Request;

class Participant extends AbstractController
{

    /**
     * Checks two conditions to determine if a general invite may be sent:
     * 1) checks if they are already a participant
     * 2) checks if they previously denied a request.
     * @param Request $request
     * @return array [exists: boolean, refused: boolean]
     */
    protected function canInviteGeneralJson(Request $request)
    {
        $email = $request->pullGetString('email');
        $exists = (bool) ParticipantFactory::getByEmail($email);
        $refused = InvitationFactory::checkNoContact($email);

        return ['exists' => $exists, 'refused' => $refused];
    }

    /**
     * Returns an array of participants who may be selected as judges.
     * @param Request $request
     * @return array
     */
    protected function judgeAvailableJson(Request $request)
    {
        $cycleId = $request->pullGetInteger('cycleId');
        /**
         * Do not return judges already assigned to this cycle.
         */
        $judgeIds = JudgeFactory::listing(['cycleId' => $cycleId, 'participantIdOnly' => true]);
        /**
         * Do not return judges who are currently nominated for this cycle.
         */
        $nominatedIds = NominationFactory::listing(['cycleId' => $cycleId, 'participantIdOnly' => true]);

        /**
         * Do not return judges who are nominated someone for this cycle.
         */
        $nominatorIds = NominationFactory::listing(['cycleId' => $cycleId, 'nominatorIdOnly' => true]);

        $options['notIn'] = array_merge($judgeIds, $nominatedIds, $nominatorIds);
        $options['asSelect'] = true;
        $options['search'] = $request->pullGetString('search', true);
        return ParticipantFactory::listing($options);
    }

    protected function listHtml()
    {
        return ParticipantView::adminList();
    }

    protected function listJson(Request $request)
    {
        $options['asSelect'] = $request->pullGetBoolean('asSelect', true);
        $options['search'] = $request->pullGetString('search', true);
        return ParticipantFactory::listing($options);
    }

    protected function put(Request $request)
    {
        $participant = ParticipantFactory::build($this->id);
        $firstName = $request->pullPutString('firstName', true);
        if ($firstName) {
            $participant->setFirstName($firstName);
        }
        $lastName = $request->pullPutString('lastName', true);
        if ($lastName) {
            $participant->setLastName($lastName);
        }
        ParticipantFactory::save($participant);
        return ['success' => true];
    }

    protected function trustPatch(Request $request)
    {
        $participant = ParticipantFactory::build($this->id);
        $participant->setTrusted($request->pullPatchBoolean('trust'));
        ParticipantFactory::save($participant);

        return ['success' => true];
    }

}
