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
use award\View\DashboardView;
use award\View\ParticipantView;
use award\Factory\ParticipantFactory;
use award\Factory\InvitationFactory;
use Canopy\Request;

class Participant extends AbstractController
{

    /**
     * Checks two conditions to determine if a general invite may be sent:
     * 1) checks if they are already a participant
     * 2) checks if they previously denied a request.
     * @param Request $request
     * @return array [exists: boolean, refused: boolean]
     */
    public function canInviteGeneralJson(Request $request)
    {
        $email = $request->pullGetString('email');
        $exists = (bool) ParticipantFactory::getByEmail($email);
        $refused = InvitationFactory::userRefusedGeneral($email);

        return ['exists' => $exists, 'refused' => $refused];
    }

    public function listHtml()
    {
        return ParticipantView::adminList();
    }

    public function listJson()
    {
        return ParticipantFactory::listing();
    }

}
