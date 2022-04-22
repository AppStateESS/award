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

class ParticipantView extends AbstractView
{

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
        return self::getTemplate('User/CreateAccount');
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

}
