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

    /**
     *  Returns stamped and username array if exists, false otherwise.
     * @param int $cycleId
     * @return array | boolean
     */
    public static function getLastJudgeRemind(int $cycleId, bool $toUnixTime = false)
    {
        extract(self::getDBWithTable());
        $table->addFieldConditional('cycleId', $cycleId);
        $table->addField('username');
        $table->addField('stamped');
        $table->addOrderBy('stamped', 'desc');
        $db->setLimit('1');
        $row = $db->selectOneRow();
        if ($toUnixTime && is_array($row)) {
            $row['stamped'] = strtotime($row['stamped']);
        }
        return $row;
    }

    public static function stampJudgeRemind(Cycle $cycle, string $username)
    {
        $log = self::build();
        $log->setCycle($cycle);
        $log->setAction('judge_remind');
        $log->setUsername($username);
        self::save($log);
    }

}
