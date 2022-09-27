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
     * - awardId:int          - only cycles with the same awardId
     * - judgeId: int         - only return cycles judged by this participant ID. This setting
     *                          overwrites the referenceId option
     * - referenceId: int     - only return cycles referenced by this participant ID. This option
     *                          if overwritten by the judgeId option.
     * - deletedOnly:bool     - only deleted cycles
     * - incompletedOnly:int  - only cycles that end in the future.
     * - nominationCount:bool - include number of submitted nominations
     * - upcoming: bool       - get upcoming and ongoing cycles.
     * - dateFormat:string    - formats the date with SQL strftime codes.
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

        if (!empty($options['judgeId'])) {
            $judgeTable = $db->addTable('award_judge');
            $judgeTable->addFieldConditional('participantId', (int) $options['judgeId']);
            $db->joinResources($table, $judgeTable, new Database\Conditional($db, $table->getField('id'), $judgeTable->getField('cycleId'), '=', 'left'));
        } elseif (!empty($options['referenceId'])) {
            $judgeTable = $db->addTable('award_judge');
            $judgeTable->addFieldConditional('participantId', (int) $options['referenceId']);
            $db->joinResources($table, $judgeTable, new Database\Conditional($db, $table->getField('id'), $judgeTable->getField('cycleId'), '='), 'left');
        }

        if (!empty($options['awardId'])) {
            $table->addFieldConditional('awardId', (int) $options['awardId']);
        }

        if (!empty($options['upcoming'])) {
            $table->addFieldConditional('endDate', time(), '>');
        }

        if (!empty($options['nominationCount'])) {
            $nominationTable = $db->addTable('award_nomination');
            $nominationId = $nominationTable->getField('id');
            $count = "count($nominationId)";

            $expression = new Database\Expression($count, 'nominations');
            $nominationTable->addField($expression, 'nominations');
            $db->joinResources(
                $table,
                $nominationTable,
                new Database\Conditional(
                    $db,
                    $table->getField('id'),
                    $nominationTable->getField('cycleId')
                    , '='), 'left');
        }

        if (!empty($options['includeAward'])) {
            $awardTbl = $db->addTable('award_award');
            $awardTbl->addField('judgeMethod');
            $awardTbl->addField('title');
            $db->joinResources($table, $awardTbl, new Database\Conditional($db, $table->getField('awardId'), $awardTbl->getField('id'), '='), 'left');
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
        /**
         * @var $cycle \award\Resource\Cycle
         */
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

    /**
     * Returns a list of cycles that a participant is judging.
     * @param int $participantId
     * @return array
     */
    public static function upcomingJudged(int $participantId)
    {
        $options['upcoming'] = true;
        $options['includeAward'] = true;
        $options['judgeId'] = $participantId;
        $options['dateFormat'] = true;
        return self::list($options);
    }

    /**
     * Returns cycle list in which a participant is serving as a reference.
     * @param int $participant
     * @return array
     */
    public static function upcomingReferences(int $participantId)
    {
        $options['upcoming'] = true;
        $options['includeAward'] = true;
        $options['referenceId'] = $participantId;
        $options['dateFormat'] = true;
        return self::list($options);
    }

}
