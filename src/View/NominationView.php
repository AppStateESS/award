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
use award\Factory\NominationFactory;
use award\Factory\CycleFactory;
use award\Factory\AwardFactory;
use award\Factory\SettingFactory;
use award\Factory\ParticipantFactory;
use award\Resource\Cycle;
use award\Resource\Award;

class NominationView extends AbstractView
{

    public static function deadlinePassed(Award $award, Cycle $cycle)
    {
        return 'The deadline for this cycle has passed. No more nominations are accepted';
    }

    public static function nominate(Award $award, Cycle $cycle)
    {
        $ignoreAward = AwardFactory::participantIgnoreValues();
        $menu = self::participantMenu('nomination');
        return $menu . self::scriptView('Nominate', ['cycle' => $cycle->getValues(), 'award' => $award->getValues($ignoreAward), 'match' => SettingFactory::useWarehouse()]);
    }

    /**
     * Gives participants a message that only certain accounts can nominate.
     */
    public static function onlyTrusted()
    {
        return self::getTemplate('Participant/OnlyTrusted');
    }

}
