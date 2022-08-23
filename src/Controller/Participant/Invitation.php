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
use award\Factory\ParticipantFactory;
use award\Factory\InvitationFactory;
use award\Factory\EmailFactory;
use award\Factory\JudgeFactory;
use award\Factory\AwardFactory;
use award\Factory\CycleFactory;

class Invitation extends AbstractController
{

    protected function acceptPatch()
    {
        $invitation = InvitationFactory::build($this->id);
        if ($invitation->invitedId !== ParticipantFactory::getCurrentParticipant()->id) {
            return ['success' => false, 'error' => 'Current participant is not the invited'];
        } else {
            if ($invitation->isJudge()) {
                InvitationFactory::confirmJudge($invitation);
                JudgeFactory::create($invitation->cycleId, $invitation->invitedId);
                EmailFactory::judgeConfirmed(AwardFactory::build($invitation->awardId), CycleFactory::build($invitation->cycleId), ParticipantFactory::build($invitation->invitedId));
            } elseif ($invitation->isReference()) {
                InvitationFactory::confirmReference($invitation);
                ReferenceFactory::create($invitation->cycleId, $invitation->invitedId);
                EmailFactory::referenceConfirmed(AwardFactory::build($invitation->awardId), CycleFactory::build($invitation->cycleId), ParticipantFactory::build($invitation->invitedId));
            }
        }
        return ['success' => true];
    }

    protected function listJson()
    {
        $options['invitedId'] = ParticipantFactory::getCurrentParticipant()->id;
        $options['includeAward'] = true;
        $options['confirm'] = AWARD_INVITATION_WAITING;
        return InvitationFactory::getList($options);
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
