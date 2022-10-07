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

use award\Resource\Participant;
use award\Resource\Cycle;
use award\AbstractClass\AbstractFactory;
use award\View\EmailView;
use phpws2\Database;

class JudgeFactory extends AbstractFactory
{

    protected static string $resourceClassName = 'award\Resource\Judge';
    protected static string $table = 'award_judge';

    public static function canSendJudgeReminder(int $cycleId)
    {
        $cycle = CycleFactory::build($cycleId);
        $now = time();
        $lastSent = CycleLogFactory::getLastJudgeRemind($cycle->id, true);
        // A reminder has not been sent yet or it has passed the grace period
        $canSend = $lastSent === false || $lastSent['stamped'] + (AWARD_JUDGE_REMINDER_GRACE * 86400) < $now;

        return ($cycle->endDate < $now && $canSend);
    }

    public static function create(int $cycleId, int $participantId)
    {
        $judge = JudgeFactory::build();
        $judge->cycleId = $cycleId;
        $judge->participantId = $participantId;
        JudgeFactory::save($judge);
    }

    public static function isJudge(int $participantId, int $cycleId)
    {
        extract(self::getDBWithTable());
        $table->addFieldConditional('participantId', $participantId);
        $table->addFieldConditional('cycleId', $cycleId);
        return (bool) $db->selectOneRow();
    }

    /**
     * Options
     * - cycleId (integer) Return only judges associated with this cycle.
     * - particpantIdOnly (boolean) Return only judge ids.
     * - includeParticipant (boolean) Add participant's email plus first and last name.
     *
     *
     * @param array $options
     * @return type
     */
    public static function listing(array $options = [])
    {
        extract(self::getDBWithTable());
        if (!empty($options['cycleId'])) {
            $table->addFieldConditional('cycleId', $options['cycleId']);
        }

        if (!empty($options['participantIdOnly'])) {
            $table->addField('participantId');
            while ($row = $db->selectColumn()) {
                $ids[] = $row;
            }
            return $ids;
        } elseif (!empty($options['includeParticipant'])) {
            $partTable = $db->addTable('award_participant');
            $partTable->addField('firstName');
            $partTable->addField('lastName');
            $partTable->addField('email');
            $db->joinResources($table, $partTable, new Database\Conditional($db, $table->getField('participantId'), $partTable->getField('id'), '='));
        }
        return $db->select();
    }

}
