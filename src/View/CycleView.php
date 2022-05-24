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

use award\Factory\AwardFactory;
use award\AbstractClass\AbstractView;
use award\Resource\Cycle;

class CycleView extends AbstractView
{

    /**
     * A cycle listing for administrators.
     * @param int $awardId
     */
    public static function adminList(int $awardId = 0)
    {
        self::scriptView('CycleList', ['defaultAwardId' => $awardId]);

        $tplValues['menu'] = self::menu('cycle');
        return self::getTemplate('Admin/CycleList', $tplValues);
    }

    public static function editForm(Cycle $cycle): string
    {
        $award = AwardFactory::build($cycle->getAwardId());
        $js['defaultCycle'] = $cycle->getValues();
        $js['awardTitle'] = $award->getTitle();
        $tplvars['menu'] = self::menu('award');
        $tplvars['script'] = self::scriptView('CycleForm', $js);
        return self::getTemplate('Admin/AdminForm', $tplvars);
    }

    /**
     * Returns a listing of cycles that need attention (vote approval, nomination ending)
     */
    public static function noteworthy(): string
    {
        return 'Noteworthy item listing';
    }

}
