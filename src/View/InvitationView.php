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

use award\AbstractClass\AbstractView;
use award\Factory\InvitationFactory;
use award\Resource\Invitation;

class InvitationView extends AbstractView
{

    /**
     * A cycle listing for administrators.
     * @param int $awardId
     */
    public static function adminList(int $awardId = 0)
    {
        $tplValues['menu'] = self::adminMenu('invitation');
        $tplValues['script'] = self::scriptView('InvitationList');
        return self::getTemplate('Admin/AdminForm', $tplValues);
    }

    public static function finalRefusal()
    {
        return self::centerCard('Invitation refused', self::getTemplate('User/FinalRefusal'));
    }

    public static function previouslyRefused(Invitation $invitation)
    {
        return self::centerCard('Refusal previously recorded', self::getTemplate('User/RefusePrevious', ['invitation' => $invitation]), 'info');
    }

    public static function refuseNew(Invitation $invitation)
    {
        return self::centerCard('Refuse new account invitation', self::getTemplate('User/RefuseNew', ['invitation' => $invitation]), 'danger');
    }

}
