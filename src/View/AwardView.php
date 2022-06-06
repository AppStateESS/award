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

    public static function deleteForm(Award $award)
    {
        $tplvars['awardTitle'] = $award->title;
        $tplvars['menu'] = self::menu('award');
        return self::getTemplate('Admin/DeleteAward', $tplvars);
    }

    public static function editForm(Award $award)
    {
        $jsparams['defaultAward'] = $award->getValues();
        $tplvars['menu'] = self::menu('award');
        $tplvars['script'] = self::scriptView('AwardForm', $jsparams);
        return self::getTemplate('Admin/AdminForm', $tplvars);
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

    /**
     * Displays the next step after creation of a new award.
     * @param Award $award
     * @return type
     */
    public static function newAward(Award $award)
    {
        $values = $award->getValues();
        return self::getTemplate('Admin/NewAward', $values);
    }

}
