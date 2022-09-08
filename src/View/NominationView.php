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
use award\Resource\Cycle;

class NominationView extends AbstractView
{

    public static function deadlinePassed(Award $award, Cycle $cycle)
    {
        return 'The deadline for this cycle has passed. No more nominations are accepted';
    }

    public static function nominate(Award $award, Cycle $cycle)
    {
        $ignoreAward = AwardFactory::participantIgnoreValues();
        return self::scriptView('Nominate', ['cycle' => $cycle->getValues(), 'award' => $award->getValues($ignoreAward)]);
    }

}
