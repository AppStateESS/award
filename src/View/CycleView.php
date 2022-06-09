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
use award\Factory\CycleFactory;
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
        $tplValues['menu'] = self::menu('cycle');
        $tplValues['script'] = self::scriptView('CycleList', ['defaultAwardId' => $awardId]);
        return self::getTemplate('Admin/AdminForm', $tplValues);
    }

    public static function currentCycleWarning(\award\Resource\Award $award)
    {
        $cycle = CycleFactory::build($award->getCycleId());
        $tpl = $cycle->getValues();
        $tpl['menu'] = self::menu('cycle');

        return self::getTemplate('Admin/CurrentCycleWarning', $tpl);
    }

    public static function editForm(Cycle $cycle): string
    {
        $award = AwardFactory::build($cycle->getAwardId());
        $js['defaultCycle'] = $cycle->getValues();
        $js['awardTitle'] = $award->getTitle();
        $tplvars['menu'] = self::menu('cycle');
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
