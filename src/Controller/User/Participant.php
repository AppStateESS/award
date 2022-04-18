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
use award\Controller\AbstractController;
use award\View\ParticipantView;
use award\Factory\ParticipantFactory;
use award\Factory\EmailFactory;

class Participant extends AbstractController
{

    /**
     * Displays a form for a user to create a new participant. Submission is sent to
     * self::createPost()
     *
     * @return string
     */
    protected function createAccountHtml()
    {
        return ParticipantView::createAccount();
    }

    /**
     * Creates a new participant in the database and sends a confirmation email.
     * If the participant already exists, a warning email is sent instead.
     *
     * @param Request $request
     * @return type
     */
    protected function createPost(Request $request)
    {
        $email = $request->pullPostString('email');
        $password = $request->pullPostString('password');
        $participant = ParticipantFactory::getByEmail($email);
        if ($participant) {
            EmailFactory::createWarningOnExisting($participant);
        } else {
            $newParticipant = ParticipantFactory::create($email, $password);
            EmailFactory::newParticipant($newParticipant);
        }
        return ['success' => true];
    }

//    protected function existsJson(Request $request)
//    {
//        return ['found' => true];
//    }

    /**
     * Displays a form for a participant to sign in to the system.
     * @return string
     */
    protected function signinHtml()
    {
        return ParticipantView::signin();
    }

}
