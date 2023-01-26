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
use award\Exception\ResourceNotFound;
use award\Factory\InvitationFactory;
use award\View\InvitationView;

class Invitation extends AbstractController
{

    public function finalRefusalHtml(Request $request)
    {
        $invitation = InvitationFactory::build($this->id);
        InvitationFactory::noContact($invitation);
        return InvitationView::finalRefusal();
    }

    /**
     * Refusal form for new participant requests. All other refusals handled
     * in the Participant controller.
     * @param Request $request
     * @return string
     * @throws ResourceNotFound
     */
    public function refuseHtml(Request $request)
    {
        $email = strtolower($request->pullGetString('email', true));
        $invitation = InvitationFactory::build($this->id);
        if (!$invitation->isNew() || $invitation->getEmail() !== $email) {
            throw new ResourceNotFound();
        }
        if ($invitation->isRefused()) {
            return InvitationView::previouslyRefused($invitation);
        }
        return InvitationView::refuseNew($invitation);
    }

}
