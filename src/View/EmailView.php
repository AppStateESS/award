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

namespace award\View;

use award\Resource\Participant;
use award\AbstractClass\AbstractView;

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

    private static function defaultEmailValues(Participant $participant)
    {
        return [
            'email' => $participant->getEmail(),
            'contactEmail' => \phpws2\Settings::get('award', 'siteContactEmail'),
            'contactName' => \phpws2\Settings::get('award', 'siteContactName')
        ];
    }

}
