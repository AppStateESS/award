<?php

declare(strict_types=1);
/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\View;

use award\Resource\Participant;
use award\AbstractClass\AbstractView;
use award\Resource\Invitation;
use award\Resource\Cycle;
use award\Resource\Award;
use award\Resource\Nomination;
use award\Factory\CycleFactory;
use award\Factory\AwardFactory;
use award\Factory\ParticipantFactory;

/**
 * This view is used solely to populate email messages. No HTML is sent to the browser.
 */
class EmailView extends AbstractView
{

    public static function existParticipantWarning(Participant $participant)
    {
        $values = self::defaultEmailValues();
        return self::getTemplate('User/Email/WarningParticipant', $values);
    }

    /**
     * Sends a general invitation on behalf of an administrator.
     * @param string $displayName Administrator display name.
     * @return type
     */
    public static function inviteNewParticipant(Invitation $invitation, string $from)
    {
        $values = self::defaultEmailValues();
        $values['from'] = $from;

        $values['signupLink'] = 'award/User/Participant/createAccount/?email=' . $invitation->email;
        $values['refuseLink'] = "award/User/Invitation/{$invitation->id}/refuse?email={$invitation->email}";
        return self::getTemplate('Admin/Email/InviteNewParticipant', $values);
    }

    public static function judgeConfirmed($award, $cycle, $participant)
    {
        $values = self::defaultEmailValues();
        $values['award'] = $award;
        $values['participant'] = $participant;
        $values['cycle'] = $cycle;
        return self::getTemplate('Admin/Email/JudgeConfirmed', $values);
    }

    public static function newParticipant(Participant $participant, string $hash)
    {
        $values = self::defaultEmailValues();
        $values['email'] = $participant->getEmail();
        $values['hash'] = $hash;
        return self::getTemplate('User/Email/NewParticipant', $values);
    }

    public static function participantJudgeInvitation(Invitation $invitation)
    {
        $values = array_merge(self::getInvitationObjects($invitation), self::defaultEmailValues());
        return self::getTemplate('Admin/Email/JudgeInvitation', $values);
    }

    public static function participantReferenceInvitation(Invitation $invitation)
    {
        $values = array_merge(self::getInvitationObjects($invitation), self::defaultEmailValues());
        return self::getTemplate('Participant/Email/ReferenceInvitation', $values);
    }

    /**
     * Content for an email reminding a reference to enter their reason information.
     * @param \award\Resource\Reference $reference
     * @param Participant $participant
     */
    public static function referenceReminder(\award\Resource\Reference $reference, Participant $participant)
    {
        $values = ['firstName' => $participant->getFirstName()];
        return self::getTemplate('Participant/Email/ReferenceReminder', $values);
    }

    public static function sendActivationReminder($participant, $hash)
    {

    }

    /**
     * Sends a linked email allowing the participant to update their email.
     * If the participant does not authenticate locally, a warning email is
     * sent instead.
     * @param Participant $participant
     * @param type $hash
     * @return type
     */
    public static function sendForgotPassword(Participant $participant, $hash)
    {
        $values = self::defaultEmailValues();

        if ($participant->getAuthType() === 0) {
            $values['forgotLink'] = "award/User/Participant/resetPassword?pid={$participant->id}&hash=$hash";
            $values['deadline'] = AWARD_HASH_DEFAULT_TIMER_HOURS;
            return self::getTemplate('User/Email/ForgotPassword', $values);
        } else {
            return self::getTemplate('User/Email/ForgotNotNeeded', $values);
        }
    }

    private static function defaultEmailValues()
    {
        $siteUrl = \Canopy\Server::getSiteUrl();
        return [
            'homeSite' => $siteUrl,
            'hostName' => \Layout::getPageTitle(true),
            'signIn' => $siteUrl . 'award/User/Participant/signIn',
            'contactEmail' => \phpws2\Settings::get('award', 'siteContactEmail'),
            'contactName' => \phpws2\Settings::get('award', 'siteContactName')
        ];
    }

    /**
     * Returns resource objects Cycle, Award, and Participant (as 'invitee' or 'nomiunator')
     *  based upon the ids
     * set in the invitation resource.
     * @param Invitation $invitation
     * @return array
     */
    private static function getInvitationObjects(Invitation $invitation)
    {
        $values = [];
        if ($invitation->cycleId) {
            $cycle = CycleFactory::build($invitation->cycleId);
            $award = AwardFactory::build($invitation->awardId);
            $values['cycle'] = $cycle;
            $values['award'] = $award;
            $values['awardTitle'] = CycleView::getFullAwardTitle($award, $cycle);
            $values['deadline'] = $cycle->formatEndDate();
        }

        if ($invitation->invitedId) {
            $values['invited'] = ParticipantFactory::build($invitation->invitedId);
        }

        if ($invitation->senderId) {
            $values['nominator'] = ParticipantFactory::build($invitation->senderId);
        }

        if ($invitation->nominatedId) {
            $values['nominated'] = ParticipantFactory::build($invitation->nominatedId);
        }
        return $values;
    }

}
