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

/**
 * This view is used solely to populate email messages. No HTML is sent to the browser.
 */
class EmailView extends AbstractView
{

    public static function existParticipantWarning(Participant $participant)
    {
        $values = self::defaultEmailValues($participant);
        return self::getTemplate('User/Email/WarningParticipant', $values);
    }

    /**
     * Sends a general invitation on behalf of an administrator.
     * @param string $displayName Administrator display name.
     * @return type
     */
    public static function inviteNewParticipant(string $displayName)
    {
        $values = [];
        $values['displayName'] = $displayName;
        $values['siteName'] = '';
        $values['signupLink'] = '';
        $values['refuseLink'] = '';
        return self::getTemplate('Admin/Email/InviteNewParticipant', $values);
    }

    public static function newParticipant(Participant $participant, string $hash)
    {
        $values = self::defaultEmailValues($participant);
        $values['email'] = $participant->getEmail();
        $values['hash'] = $hash;
        return self::getTemplate('User/Email/NewParticipant', $values);
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
        $values = self::defaultEmailValues($participant);

        if ($participant->getAuthType() === 0) {
            $values['forgotLink'] = "award/User/Participant/resetPassword?pid={$participant->id}&hash=$hash";
            $values['deadline'] = AWARD_HASH_DEFAULT_TIMER_HOURS;
            return self::getTemplate('User/Email/ForgotPassword', $values);
        } else {
            return self::getTemplate('User/Email/ForgotNotNeeded', $values);
        }
    }

    private static function defaultEmailValues(Participant $participant)
    {
        return [
            'homeSite' => \Canopy\Server::getSiteUrl(),
            'hostName' => \Layout::getPageTitle(true),
            'email' => $participant->getEmail(),
            'contactEmail' => \phpws2\Settings::get('award', 'siteContactEmail'),
            'contactName' => \phpws2\Settings::get('award', 'siteContactName')
        ];
    }

}
