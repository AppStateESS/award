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

class AwardView extends AbstractView
{

    public static function frontPage()
    {
        $values = ['signedIn' => \award\Factory\ParticipantFactory::isSignedIn()];
        return self::getTemplate('UserFrontPage', $values, true);
    }

}
