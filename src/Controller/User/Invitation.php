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

namespace award\Controller\User;

use Canopy\Request;
use award\AbstractClass\AbstractController;
use award\View\InvitationView;
use award\Factory\InvitationFactory;

class Invitation extends AbstractController
{

    public function refuseHtml(Request $request)
    {
        $invitationId = $this->id;
        $email = strtolower($request->pullGetString('email'));
        $invitation = InvitationFactory::build($this->id);
        if ($invitation->email !== $email) {
            throw new \award\Exception\ResourceNotFound;
        }
        InvitationView::refuse($invitationId);
    }

}
