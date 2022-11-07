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
use award\Factory\EmailFactory;
use award\Factory\InvitationFactory;
use award\Factory\JudgeFactory;
use award\Factory\NominationFactory;
use award\Factory\ParticipantFactory;
use award\Factory\ReferenceFactory;

class Invitation extends AbstractController
{

    protected function acceptPatch()
    {
        $invitation = InvitationFactory::build($this->id);
        if ($invitation->invitedId !== ParticipantFactory::getCurrentParticipant()->id) {
            return ['success' => false, 'error' => 'Current participant is not the invited'];
        } else {
            if ($invitation->isJudge()) {
                JudgeFactory::create($invitation->cycleId, $invitation->invitedId);
                EmailFactory::judgeConfirmed(AwardFactory::build($invitation->awardId), CycleFactory::build($invitation->cycleId), ParticipantFactory::build($invitation->invitedId));
            } elseif ($invitation->isReference()) {
                ReferenceFactory::create($invitation->cycleId, $invitation->nominationId, $invitation->invitedId);
                EmailFactory::referenceConfirmed(AwardFactory::build($invitation->awardId), CycleFactory::build($invitation->cycleId), ParticipantFactory::build($invitation->invitedId));
            }
            InvitationFactory::confirm($invitation);
        }
        return ['success' => true];
    }

    /**
     * JSON listing of reference invitations.
     * @return array
     */
    protected function listJson()
    {
        $options['invitedId'] = ParticipantFactory::getCurrentParticipant()->id;
        $options['includeAward'] = true;
        $options['includeNominated'] = true;
        $options['confirm'] = AWARD_INVITATION_WAITING;

        return InvitationFactory::listing($options);
    }

    protected function participantReferencePost(Request $request)
    {
        $cycleId = $request->pullPostInteger('cycleId');

        $invitedParticipant = ParticipantFactory::build($request->pullPostInteger('invitedId'));
        $nomination = NominationFactory::build($request->pullPostInteger('nominationId'));
        $invitation = InvitationFactory::createReferenceInvitation($invitedParticipant, $cycleId, $nomination);

        EmailFactory::sendParticipantReferenceInvitation($invitation);
        return ['success' => true];
    }

    protected function referenceJson(Request $request)
    {
        $nomination = NominationFactory::build($request->pullGetInteger('nominationId'));
        $options['cycleId'] = $nomination->cycleId;
        $options['senderId'] = ParticipantFactory::getCurrentParticipant()->id;
        $options['includeInvited'] = true;
        return InvitationFactory::listing($options);
    }

    protected function refusePatch()
    {
        $invitation = InvitationFactory::build($this->id);
        if ($invitation->invitedId !== ParticipantFactory::getCurrentParticipant()->id) {
            return ['success' => false, 'error' => 'Current participant is not the invited'];
        } else {
            InvitationFactory::refuseJudge($invitation);
            EmailFactory::judgeRefused(AwardFactory::build($invitation->awardId), CycleFactory::build($invitation->cycleId), ParticipantFactory::build($invitation->invitedId));
        }
        return ['success' => true];
    }

}
