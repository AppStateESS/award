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
use award\Resource\Cycle;
use award\Factory\AwardFactory;
use phpws2\Database;
use Canopy\Request;

class CycleFactory extends AbstractFactory
{

    protected static string $table = 'award_cycle';
    protected static string $resourceClassName = 'award\Resource\Cycle';

    public static function currentList(int $awardId)
    {
        if (!$awardId) {
            throw new \Exception('Non-zero id expected');
        }
        extract(self::getDBWithTable());

        $table->addField('id');
        $table->addField('awardYear');
        $table->addField('awardMonth');
        $table->addFieldConditional('awardId', $awardId);
        $table->addFieldConditional('awardYear', strftime('%Y'), '>=');
        $table->addFieldConditional('deleted', 0);
        return $db->select();
    }

    /**
     * Flips the deleted flag on the Cycle resource and saves it.
     * @param int $cycleId
     */
    public static function delete(int $cycleId)
    {
        $cycle = self::build($cycleId);
        $cycle->setDeleted(true);
        self::save($cycle);
        AwardFactory::clearCycle($cycle->awardId);
    }

    /**
     * Sets the deleted flag to true on all cycles associated with
     * the award id.
     * @param int $awardId
     * @return int
     */
    public static function deleteByAwardId(int $awardId): int
    {
        extract(self::getDBWithTable());

        $table = $db->addTable('award_cycle');
        $table->addFieldConditional('awardId', $awardId);
        $table->addValue('deleted', 1);
        return $db->update();
    }

    /**
     * Returns the award ID for a cycle by primary key.
     * @param int $cycleId
     * @return int
     */
    public static function getAwardId(int $cycleId): int
    {
        extract(self::getDBWithTable());
        $table->addField('awardId');
        $table->addFieldConditional('id', $cycleId);
        return $db->selectColumn();
    }

    /**
     * Returns an array of cycles.
     * Options
     * - awardId:int - only cycles with the same awardId
     * - deletedOnly:bool - only deleted cycles
     * - incompletedOnly:int - only cycles that end in the future.
     * - dateFormat:string - formats the date with SQL strftime codes.
     * @param array $options
     * @return array
     */
    public static function list(array $options = [])
    {
        extract(self::getDBWithTable());
        $table->addField('id');
        $table->addField('awardMonth');
        $table->addField('awardYear');
        $table->addField('voteAllowed');
        $table->addField('voteType');
        $table->addField('term');

        if (!empty($options['dateFormat'])) {
            $format = '%l:%i %p, %b %e, %Y';
            if (is_string($options['dateFormat'])) {
                $format = is_string($options['dateFormat']);
            }
            $startDateExpression = $db->getExpression('FROM_UNIXTIME(' . $table->getField('startDate') . ', "' . $format . '")', 'startDate');
            $endDateExpression = $db->getExpression('FROM_UNIXTIME(' . $table->getField('endDate') . ', "' . $format . '")', 'endDate');
            $table->addField($startDateExpression);
            $table->addField($endDateExpression);
        } else {
            $table->addField('startDate');
            $table->addField('endDate');
        }

        if (!empty($options['awardId'])) {
            $table->addFieldConditional('awardId', (int) $options['awardId']);
        }

        if (!empty($options['includeAward'])) {
            $awardTbl = $db->addTable('award_award');
            $awardTbl->addField('judgeMethod');
            $awardTbl->addField('title');
            $db->joinResources($table, $awardTbl, new Database\Conditional($db, $table->getField('awardId'), $awardTbl->getField('id'), '='));
        }


        if (!empty($options['deletedOnly'])) {
            $table->addFieldConditional('deleted', 1);
        } else {
            $table->addFieldConditional('deleted', 0);
        }

        if (!empty($options['incompleteOnly'])) {
            $table->addFieldConditional('completed', 0);
        }

        $table->addOrderBy('startDate', 'desc');
        return $db->select();
    }

    /**
     * Copies the post information to cycle object.
     * Does not set currentActive or voteAllowed. Both default to false.
     * @param Request $request
     */
    public static function post(Request $request)
    {
        $cycle = self::build();

        $cycle->setAwardId($request->pullPostInteger('awardId'));
        $cycle->setAwardMonth($request->pullPostInteger('awardMonth'));
        $cycle->setAwardYear($request->pullPostInteger('awardYear'));
        $cycle->setEndDate($request->pullPostInteger('endDate'));
        $cycle->setStartDate($request->pullPostInteger('startDate'));
        $cycle->setTerm($request->pullPostString('term'));
        $cycle->setVoteType($request->pullPostString('voteType'));
        return $cycle;
    }

    /**
     * Copies the post information to cycle object.
     * Does not set currentActive or voteAllowed. Both default to false.
     * @param Request $request
     */
    public static function put(int $cycleId, Request $request)
    {
        $cycle = self::build($cycleId);

        $cycle->setEndDate($request->pullPutInteger('endDate'));
        $cycle->setStartDate($request->pullPutInteger('startDate'));
        $cycle->setVoteType($request->pullPutString('voteType'));
        return $cycle;
    }

}
