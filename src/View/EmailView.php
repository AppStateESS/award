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

class EmailView extends AbstractView
{

    public static function newParticipant(Participant $participant)
    {
        $values = [
            'email' => $participant->getEmail(),
            'hash' => $participant->getHash(),
            'contactEmail' => \phpws2\Settings::get('award', 'siteContactEmail'),
            'contactName' => \phpws2\Settings::get('award', 'siteContactName')
        ];
        return self::getTemplate('User/Email/NewParticipant', $values);
    }

}
