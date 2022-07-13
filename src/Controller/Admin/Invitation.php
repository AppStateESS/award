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
        $options['awardId'] = $request->pullGetInteger('awardId', true);
        $options['confirm'] = $request->pullGetInteger('confirm', true);
        $options['inviteType'] = $request->pullGetInteger('inviteType', true);

        return InvitationFactory::getList($options);
    }

    public function post(Request $request)
    {
        $email = $request->pullPostString('email');
        $type = $request->pullPostInteger('type');
        switch ($type) {
            case AWARD_INVITE_TYPE_NEW:
                if (InvitationFactory::sendGeneral($email)) {
                    InvitationFactory::createGeneral($email);
                    return ['result' => 'sent'];
                } else {
                    // Tried to send an invite that was previously sent.
                    return ['result' => 'notsent'];
                }
        }
    }

}
