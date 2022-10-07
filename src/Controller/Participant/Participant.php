<?php

declare(strict_types=1);

/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * This class is the controller for authenticated Participants.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\Controller\Participant;

use Canopy\Request;
use award\AbstractClass\AbstractController;
use award\View\ParticipantView;
use award\Factory\ParticipantFactory;
use award\Factory\EmailFactory;
use award\Factory\Authenticate;

class Participant extends AbstractController
{

    protected function dashboardHtml()
    {
        return ParticipantView::dashboard();
    }

    protected function searchNomineesJson(Request $request)
    {
        if (!ParticipantFactory::currentIsTrusted()) {
            return ['error' => 'untrusted'];
        }
        $options['search'] = $request->pullGetString('search');
        $options['cycleId'] = $request->pullGetInteger('cycleId');

        return ParticipantFactory::listing($options);
    }

    protected function signoutHtml()
    {
        $participant = ParticipantFactory::getCurrentParticipant();

        \award\Factory\ParticipantFactory::signOut();
        \award\Factory\AuthenticateFactory::signOut($participant->authType);
        return ParticipantView::signedOut();
    }

}
