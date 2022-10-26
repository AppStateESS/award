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
use award\Factory\InvitationFactory;
use award\Factory\ParticipantFactory;
use award\View\InvitationView;
use award\Factory\EmailFactory;
use award\Resource\Participant;
use Canopy\Request;

class Invitation extends AbstractController
{

    /**
     * HTML listing of Awards seen by the admin.
     * @return type
     */
    protected function listHtml()
    {
        return InvitationView::adminList();
    }

    protected function listJson(Request $request)
    {

        $options['confirm'] = $request->pullGetInteger('confirm', true) ? $request->pullGetInteger('confirm') : null;
        $options['inviteType'] = $request->pullGetInteger('inviteType', true) ? $request->pullGetInteger('inviteType') : null;
        $options['awardId'] = (int) $request->pullGetInteger('awardId', true);
        $options['cycleId'] = (int) $request->pullGetInteger('cycleId', true);
        $options['includeInvited'] = $request->pullGetBoolean('includeInvited');

        return InvitationFactory::getList($options);
    }

    protected function participantJudgePost(Request $request)
    {
        $cycleId = $request->pullPostInteger('cycleId');
        $invitedId = $request->pullPostInteger('invitedId');
        $invited = ParticipantFactory::build($invitedId);
        $result = $this->testParticipant($invited, $cycleId, AWARD_INVITE_TYPE_JUDGE);
        if (is_array($result)) {
            return $result;
        }

        $invitation = InvitationFactory::createJudgeInvitation($invited, $cycleId);
        EmailFactory::sendParticipantJudgeInvitation($invitation);
        return ['success' => true];
    }

    protected function participantReferencePost(Request $request)
    {
        $cycleId = $request->pullPostInteger('cycleId');
        $invitedId = $request->pullPostInteger('invitedId');
        $invited = ParticipantFactory::build($invitedId);
        $result = $this->testParticipant($invited, $cycleId, AWARD_INVITE_TYPE_REFERENCE);
        if (is_array($result)) {
            return $result;
        }

        $invitation = InvitationFactory::createReferenceInvitation($invited, $cycleId);
        EmailFactory::sendParticipantReferenceInvitation($invitation);
        return ['success' => true];
    }

    public function post(Request $request)
    {
        $email = $request->pullPostString('email');
        $type = $request->pullPostInteger('type');

        // check if a previous invitation was sent to join the site.
        $previousInvite = InvitationFactory::getPreviousInvite($email, AWARD_INVITE_TYPE_NEW);

        // If it exists, then stop here and send back the reason.
        if ($previousInvite) {
            return ['result' => 'notsent', 'confirm' => $previousInvite->confirm];
        }

        switch ($type) {
            case AWARD_INVITE_TYPE_NEW:
                return self::newParticipant($email);
        }
    }

    /**
     * Creates a new participate invitation and sends an email to bring them to the site.
     * If the person refuses, they may not serve as a judge, nominator, or reference.
     * @param type $email
     * @return type
     */
    private function newParticipant($email)
    {
        $invitation = InvitationFactory::createNewAccountInvite($email);

        if (EmailFactory::inviteNewParticipant($invitation, \Current_User::getDisplayName())) {
            return ['result' => 'sent'];
        } else {
            return ['result' => 'notsent', 'reason' => 'failed to send email'];
        }
    }

    /**
     * Determines if a participant should be sent an invitation.
     * If the participant is not viable or has been previously invited, an array with success status
     * and a message are returned for a JSON response.
     * @param Participant $participant
     * @param int $cycleId
     * @return array
     */
    private function testParticipant(Participant $participant, int $cycleId, $inviteType)
    {
        if (!$participant) {
            return ['success' => false, 'message' => 'Cannot create invitation because the invited participant was not found.'];
        }

        try {
            ParticipantFactory::isViable($participant);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Cannot create invitation because ' . $e->getMessage()];
        }

        $previousInvite = InvitationFactory::getPreviousInvite($participant->email, $inviteType, $cycleId);
        if ($previousInvite) {
            return ['success' => false, 'message' => 'Cannot create invitation because ' . InvitationFactory::confirmReason($previousInvite->confirm)];
        }
    }

}
