<?php

/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\Authtypes;

use award\InterfaceClass\InterfaceAuthentication;

/**
 * This class is specifically for AppState.
 */
class AppstateShibboleth implements InterfaceAuthentication
{

    public function getEmail(): string
    {
        return $_SERVER['HTTP_EPPN'] ?? false;
    }

    public function getLogin(): string
    {
        return PHPWS_HOME_HTTP . '/secure/';
    }

    public function getLogout(): string
    {
        return PHPWS_HOME_HTTP . '/logout.php';
    }

    public function getTitle(): string
    {
        return 'Appalachian State Shibboleth';
    }

    public function getUserName(): array
    {

    }

    /**
     *
     */
    public function initialize()
    {

    }

    public function isLoggedIn(): bool
    {
        return isset($_SERVER['HTTP_EPPN']);
    }

    public function logout()
    {

    }

}
