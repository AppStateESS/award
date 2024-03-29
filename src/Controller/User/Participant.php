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
use award\View\ParticipantView;
use award\Factory\Authenticate;
use award\Factory\EmailFactory;
use award\Factory\InvitationFactory;
use award\Factory\ParticipantFactory;
use award\Factory\ParticipantHashFactory;

class Participant extends AbstractController
{

    protected function authorizeHtml(Request $request)
    {
        $hash = $request->pullGetString('hash');
        $pid = $request->pullGetInteger('pid');
        if (!$participant = ParticipantFactory::build($pid)) {
            return ParticipantView::authorizeFailed();
        }
        $checkHash = ParticipantHashFactory::get($participant->getId());
        if ($checkHash['timeout'] < time()) {
            return ParticipantView::authorizeTimeout();
        } else {
            ParticipantFactory::authorize($participant);
            return ParticipantView::authorizeComplete();
        }
    }

    /**
     * Displays a form for a user to create a new participant. Submission is sent to
     * self::createPost()
     *
     * @return string
     */
    protected function createAccountHtml(Request $request)
    {
        if (ParticipantFactory::getCurrentParticipant()) {
            return 'User:Participant:createAccountHTML already logged in as participant';
        } else {
            $email = $request->pullGetString('email', true);
            return ParticipantView::createAccount($email ? $email : '');
        }
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
        $firstName = $request->pullPostString('firstName');
        $lastName = $request->pullPostString('lastName');
        $participant = ParticipantFactory::getByEmail($email);
        if ($participant) {
            EmailFactory::createWarningOnExisting($participant);
        } else {
            $newParticipant = ParticipantFactory::createInternal($email, $password, $firstName, $lastName);
            $hash = ParticipantHashFactory::create($newParticipant->id);
            EmailFactory::newParticipant($newParticipant, $hash);
            InvitationFactory::confirmNewStatus($newParticipant->getEmail());
        }
        return ['success' => true];
    }

    /**
     * View letting the user know their confirmation email was sent.
     * @return string
     */
    protected function emailSentHtml()
    {
        return ParticipantView::emailSent();
    }

    /**
     * A catch all error page for users.
     * @return string
     */
    protected function errorHtml()
    {
        return ParticipantView::error();
    }

    protected function forgotPasswordHtml()
    {
        return ParticipantView::forgotPassword();
    }

    protected function forgotPasswordPost(Request $request)
    {
        $email = strtolower($request->pullPostString('email'));
        ParticipantFactory::sendForgotEmail($email);
        return ParticipantView::forgotPasswordPost($email);
    }

    protected function invalidHash()
    {
        return ParticipantView::invalidHash();
    }

    protected function passwordChangeCompleteHtml()
    {
        return ParticipantView::passwordChangeComplete();
    }

    protected function resetPasswordHtml(Request $request)
    {
        $participantId = $request->pullGetInteger('pid');
        $hash = $request->pullGetString('hash');
        if (ParticipantHashFactory::isValid($participantId, $hash)) {
            return ParticipantView::resetPassword($participantId, $hash);
        } else {
            return ParticipantView::invalidHash();
        }
    }

    /**
     * If the hash is valid, resets the participant's password.
     * @param Request $request
     * @return array
     */
    protected function resetPasswordPut(Request $request)
    {
        $password = $request->pullPutString('password');
        $hash = $request->pullPutString('hash');
        if (ParticipantHashFactory::isValid($this->id, $hash)) {
            ParticipantFactory::resetPassword($this->id, $password);
            ParticipantHashFactory::remove($this->id);
            return ['success' => true];
        } else {
            return ['success' => false];
        }
    }

    protected function saveNewAccountHtml(Request $request)
    {
        if (!Authenticate::isLoggedIn()) {
            return ParticipantView::notLoggedInError();
        }
        $token = $request->pullGetString('token');
        $matchToken = ParticipantFactory::getCreateToken();
        if ($matchToken !== $token || !Authenticate::isLoggedIn()) {
            return ParticipantView::signInCreateError();
        } else {
            $loggedEmail = Authenticate::getLoginEmail();
            return "Create account for $loggedEmail";
        }
    }

    /**
     * Displays a form for a participant to sign in to the system.
     * @return string
     */
    protected function signInHtml()
    {
        return ParticipantView::signIn();
    }

    /**
     * Receives sign post of email and password.
     * @param Request $request
     */
    protected function signInPost(Request $request)
    {
        $email = $request->pullPostString('email');
        $password = $request->pullPostString('password');
        return ParticipantFactory::authenticate($email, $password);
    }

}
