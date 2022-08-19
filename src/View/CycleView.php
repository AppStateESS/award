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
use award\Factory\InvitationFactory;
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
        // Uses cycleId from the Judges script
        $values['invitationStatus'] = self::scriptView('CycleInvitationStatus');

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
     * Used by Dashboard view
     */
    public static function upcoming(): string
    {
        $options = ['incompleteOnly' => true, 'includeAward' => true];

        $cycleList = CycleFactory::list($options);

        $today = time();
        $format = '%b. %e, %Y - %l:%M %p';
        foreach ($cycleList as &$cycle) {
            if ($cycle['startDate'] > $today) {
                $cycle['label'] = 'Nominations start ' . stftime($format, $cycle['startDate']);
            } elseif ($cycle['endDate'] > $today) {
                $cycle['label'] = 'Nominations deadline ' . strftime($format, $cycle['endDate']);
            } else {
                $cycle['label'] = 'Voting ready';
            }
        }
        $values['cycleList'] = $cycleList;

        return self::getTemplate('Admin/UpcomingCycles', $values);
    }

}
