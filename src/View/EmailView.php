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

    public static function newParticipant(Participant $participant)
    {
        $values = self::defaultEmailValues($participant);
        $values['email'] = $participant->getEmail();
        return self::getTemplate('User/Email/NewParticipant', $values);
    }

    public static function existParticipantWarning(Participant $participant)
    {
        $values = self::defaultEmailValues($participant);
        return self::getTemplate('User/Email/WarningParticipant', $values);
    }

    private static function defaultEmailValues(Participant $participant)
    {
        return [
            'email' => $participant->getEmail(),
            'hash' => $participant->getHash(),
            'contactEmail' => \phpws2\Settings::get('award', 'siteContactEmail'),
            'contactName' => \phpws2\Settings::get('award', 'siteContactName')
        ];
    }

}
