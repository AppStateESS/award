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

    public static function createAccount()
    {
        $values = [];
        self::scriptView('SignUpForm');
        return self::getTemplate('User/createAccount', $values);
    }

    public static function signIn()
    {
        $values = [];
        return self::getTemplate('User/SignIn', $values);
    }

}
