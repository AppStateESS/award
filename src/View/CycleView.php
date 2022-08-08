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
use award\Resource\Award;
use award\Resource\Cycle;

class CycleView extends AbstractView
{

    /**
     * A cycle listing for administrators.
     * @param int $awardId
     */
    public static function adminList(int $awardId = 0)
    {
        $values['menu'] = self::menu('cycle');
        $values['script'] = self::scriptView('CycleList', ['defaultAwardId' => $awardId]);
        return self::getTemplate('Admin/AdminForm', $values);
    }

    public static function adminView(Cycle $cycle, Award $award)
    {
        $values['menu'] = self::menu('cycle');
        $values['award'] = $award;
        $values['cycle'] = $cycle;
        $values['startDate'] = $cycle->formatStartDate();
        $values['endDate'] = $cycle->formatStartDate();
        if ($award->judgeMethod === 1) {
            $values['judges'] = self::scriptView('Judges', ['cycleId' => $cycle->id]);
        } else {
            $values['judges'] = 'No judges, popular vote';
        }

        return self::getTemplate('Admin/CycleView', $values);
    }

    public static function currentCycleWarning(\award\Resource\Award $award)
    {
        $cycle = CycleFactory::build($award->getCycleId());
        $values = $cycle->getValues();
        $values['menu'] = self::menu('cycle');

        return self::getTemplate('Admin/CurrentCycleWarning', $values);
    }

    public static function editForm(Cycle $cycle): string
    {
        $award = AwardFactory::build($cycle->getAwardId());
        $js['defaultCycle'] = $cycle->getValues();
        $js['awardTitle'] = $award->getTitle();
        $values['menu'] = self::menu('cycle');
        $values['script'] = self::scriptView('CycleForm', $js);
        return self::getTemplate('Admin/AdminForm', $values);
    }

    /**
     * Returns a listing of cycles that need attention (vote approval, nomination ending)
     */
    public static function noteworthy(): string
    {
        return 'Noteworthy item listing';
    }

}
