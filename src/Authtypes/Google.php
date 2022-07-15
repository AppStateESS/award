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

namespace award\Authtypes;

use award\InterfaceClass\InterfaceAuthentication;

class Google implements InterfaceAuthentication
{

    public function getEmail(): string
    {

    }

    public function getLogin(): string
    {

    }

    public function getLogout(): string
    {

    }

    public function getTitle(): string
    {
        return 'Google Identity';
    }

    public function getUserName(): array
    {

    }

    public function initialize()
    {

    }

    public function isLoggedIn(): bool
    {

    }

    public function logout()
    {

    }

}
