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

use award\AbstractClass\AbstractView;
use award\Resource\Award;

class AwardView extends AbstractView
{

    public static function adminList()
    {
        $params['menu'] = self::menu('award');
        self::scriptView('AwardList');
        return self::getTemplate('Admin/AwardList', $params);
    }

    public static function editForm(Award $award)
    {
        $params['defaultAward'] = $award->getValues();
        return self::scriptView('AwardForm', $params);
    }

    /**
     * Home page introduction page.
     * @return string
     */
    public static function frontPage()
    {
        $values = ['signedIn' => \award\Factory\ParticipantFactory::isSignedIn()];
        return self::getTemplate('User/FrontPage', $values, true);
    }

}
