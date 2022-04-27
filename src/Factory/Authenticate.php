<?php

/**
 * This is written strictly for Shibboleth. Should authentication change in some manner it will
 * need to be reworked.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\Factory;

class Authenticate
{

    public static function isLoggedIn()
    {
        return isset($_SERVER[AWARD_SHIB_USERNAME_TAG]);
    }

    /**
     * Returns the email address of the currently logged in user.
     * @return string | false
     */
    public static function getLoginEmail()
    {
        return $_SERVER[AWARD_SHIB_USERNAME_TAG] ?? false;
    }

    public static function getLoginLink()
    {
        return \Current_User::getAuthorization()->login_link;
    }

    public static function getLogoutLink()
    {
        return \Current_User::getAuthorization()->logout_link;
    }

    /**
     * Returns the username of the currently logged in user.
     * @return string | false
     */
    public static function getLoginUsername()
    {
        if (!isset($_SERVER[AWARD_SHIB_USERNAME_TAG])) {
            return false;
        }
        return str_ireplace(AWARD_SHIB_DOMAIN, '', $_SERVER[AWARD_SHIB_USERNAME_TAG]);
    }

    public static function sendToLogin()
    {
        \Canopy\Server::forward(self::getLoginLink());
    }

    public static function logoutUrl()
    {
        return self::getLogoutLink();
    }

}
