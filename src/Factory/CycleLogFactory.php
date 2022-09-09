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

namespace award\Factory;

use award\AbstractClass\AbstractFactory;
use award\Resource\CycleLog;
use award\Resource\Cycle;
use phpws2\Database;
use Canopy\Request;
use award\Exception\ResourceNotFound;

class CycleLogFactory extends AbstractFactory
{

    protected static string $table = 'award_cyclelog';
    protected static string $resourceClassName = 'award\Resource\CycleLog';

    public static function stampJudgeRemind(Cycle $cycle)
    {
        $log = self::build();
        $log->setCycle($cycle);
        $log->setAction('judge_remind');
        self::save($log);
    }

}
