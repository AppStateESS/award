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
use award\Factory\JudgeFactory;
use award\Factory\CycleFactory;
use award\Factory\AwardFactory;
use award\Resource\Cycle;
use award\Resource\Award;

class JudgeView extends AbstractView
{

    /**
     * Error page for an action that requires judges to proceed.
     * @return string
     */
    public static function noJudges(Cycle $cycle)
    {
        $award = AwardFactory::build($cycle->awardId);
        $values['awardTitle'] = self::getFullAwardTitle($award, $cycle);
        $menu = self::menu('cycle');
        return $menu . self::getTemplate('Admin/Error/NoJudges', $values);
    }

    public static function remind(Cycle $cycle)
    {
        $award = AwardFactory::build($cycle->awardId);
        $values['awardTitle'] = self::getFullAwardTitle($award, $cycle);
        $values['cycleId'] = $cycle->id;
        $values['judgeHeader'] = self::remindHeader($cycle);
        return self::getTemplate('Admin/RemindJudges', $values);
    }

    public static function remindHeader(Cycle $cycle)
    {
        $award = AwardFactory::build($cycle->awardId);
        $values['awardTitle'] = self::getFullAwardTitle($award, $cycle);
        $values['endDate'] = $cycle->formatEndDate('%B %e');
        $values['endDateTime'] = $cycle->formatEndDate('%l:%M %p');
        $values['signInUrl'] = PHPWS_HOME_HTTP . 'award/User/Participant/signIn';
        return self::getTemplate('Admin/RemindJudgeHeader', $values);
    }

}
