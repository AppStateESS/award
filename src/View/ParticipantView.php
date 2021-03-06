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

use award\Factory\ParticipantFactory;
use award\Factory\AuthenticateFactory;
use award\AbstractClass\AbstractView;

class ParticipantView extends AbstractView
{

    public static function adminList()
    {
        $params['menu'] = self::menu('participant');
        $params['script'] = self::scriptView('ParticipantList');
        return self::getTemplate('Admin/AdminForm', $params);
    }

    public static function authorizeComplete()
    {
        return self::getTemplate('User/AuthorizeComplete');
    }

    public static function authorizeFailed()
    {

        return self::getTemplate('User/AuthorizeFailed');
    }

    /**
     * Displays the sign up form for new participant accounts.
     *
     * @return string
     */
    public static function createAccount()
    {
        self::scriptView('SignUpForm');
        $vars['signinButtons'] = AuthenticateFactory::getSignInButtons();
        return self::getTemplate('User/CreateAccount', $vars, true);
    }

    /**
     * A view for users logged in to SSO.
     * @param string $email Email address of SSO participant
     * @return string
     */
    public static function createSignedInAccount(string $email)
    {
        $token = ParticipantFactory::loadCreateToken();
        return self::getTemplate('User/CreateSignedInAccount',
                [
                    'email' => $email,
                    'signUpLink' => "./award/User/Participant/saveNewAccount?token=$token"
                ]
        );
    }

    /**
     * Informs the user their account request is saved and to check
     * their email for confirmation.
     *
     * @return string
     */
    public static function emailSent()
    {
        return self::getTemplate('User/EmailSent');
    }

    public static function error()
    {
        return self::getTemplate('User/Error', ['contactEmail' => \phpws2\Settings::get('award', 'siteContactEmail')]);
    }

    public static function forgotPassword()
    {
        return self::scriptView('ForgotPassword');
    }

    public static function forgotPasswordPost($email)
    {
        return self::getTemplate('User/ForgotPost', ['email' => $email]);
    }

    public static function notLoggedInError()
    {
        return self::getTemplate('User/NotLoggedIn', ['loginLink' => \award\Factory\Authenticate::getLoginLink()]);
    }

    /**
     * Show the sign in form for current participants.
     *
     * @return string
     */
    public static function signIn()
    {
        self::scriptView('SignInForm');
        return self::getTemplate('User/SignIn');
    }

    /**
     * Shows an error if the attempt at creating a participant failed due
     * to not being logged in or the token was wrong.
     *
     * @return string
     */
    public static function signInCreateError()
    {

    }

}
