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
use award\Exception\CycleComplete;
use award\Exception\CycleEndDatePassed;
use award\Exception\CycleNotStarted;
use award\Exception\NominationExpired;
use award\Exception\ResourceNotFound;
use award\Factory\AwardFactory;
use award\Resource\Cycle;
use Canopy\Request;
use phpws2\Database;

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
     *                          overwrites the participantId option
     * - participantId: int     - only return cycles referenced by this participant ID. This option
     *                          if overwritten by the judgeId option.
     * - deletedOnly:bool     - only deleted cycles
     * - incompletedOnly:int  - only cycles that end in the future.
     * - nominationCount:bool - include number of submitted nominations
     * - upcoming: bool       - get upcoming and ongoing cycles.
     * - dateFormat:string    - formats the date with SQL strftime codes.
     * @param array $options
     * @return array
     */
    public static function listing(array $options = [])
    {
        extract(self::getDBWithTable());
        $table->addField('id');
        $table->addField('awardMonth');
        $table->addField('awardYear');
        $table->addField('voteAllowed');
        $table->addField('voteType');
        $table->addField('term');
        $table->addField('startDate');
        $table->addField('endDate');

        if (!empty($options['dateFormat'])) {
            self::formatDates($db, $table, $options['dateFormat']);
        }

        if (!empty($options['judgeId'])) {
            $judgeTable = $db->addTable('award_judge');
            $judgeTable->addFieldConditional('participantId', (int) $options['judgeId']);
            $db->joinResources($table, $judgeTable, new Database\Conditional($db, $table->getField('id'), $judgeTable->getField('cycleId'), '=', 'left'));
        } elseif (!empty($options['participantId'])) {
            $referenceTable = $db->addTable('award_reference');
            $referenceTable->addFieldConditional('participantId', (int) $options['participantId']);
            $db->joinResources($table, $referenceTable, new Database\Conditional($db, $table->getField('id'), $referenceTable->getField('cycleId'), '='), 'left');
        }

        if (!empty($options['awardId'])) {
            $table->addFieldConditional('awardId', (int) $options['awardId']);
        }

        if (!empty($options['upcoming'])) {
            $table->addFieldConditional('endDate', time(), '>');
        }

        if (!empty($options['nominationCount'])) {
            $db->setGroupBy($table->getField('id'));
            self::addNominationCount($db, $table);
        }

        if (!empty($options['includeAward'])) {
            $awardTbl = $db->addTable('award_award');
            $awardTbl->addField('judgeMethod');
            $awardTbl->addField('title', 'awardTitle');
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

    public static function nominationAllowed(Cycle $cycle)
    {
        if ($cycle->getEndDate() < time()) {
            throw new CycleEndDatePassed($cycle->id);
        }
        if ($cycle->getDeleted()) {
            throw new ResourceNotFound();
        }
        if ($cycle->getStartDate() > time()) {
            throw new CycleNotStarted($cycle->id);
        }
        if ($cycle->getCompleted()) {
            throw new CycleComplete($cycle->id);
        }
        return true;
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
        // New cycle, sync last end date with current setting.
        $cycle->setLastEndDate($cycle->getEndDate());
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
        $cycle->stampLastEndDate();
        $cycle->setEndDate($request->pullPutInteger('endDate'));
        $cycle->setStartDate($request->pullPutInteger('startDate'));
        $cycle->setVoteType($request->pullPutString('voteType'));
        return $cycle;
    }

    /**
     * Returns a list of cycles that a participant is judging.
     * Not sure is used.
     * @param int $participantId
     * @return array
     */
    public static function upcomingJudged(int $participantId)
    {
        $options['upcoming'] = true;
        $options['includeAward'] = true;
        $options['judgeId'] = $participantId;
        $options['dateFormat'] = true;
        return self::listing($options);
    }

    /**
     * Returns cycle list in which a participant is serving as a reference.
     * Not sure if in use.
     * @param int $participant
     * @return array
     */
    public static function upcomingReferences(int $participantId)
    {
        $options['upcoming'] = true;
        $options['includeAward'] = true;
        $options['includeNominated'] = true;
        $options['participantId'] = $participantId;
        $options['dateFormat'] = true;
        return self::listing($options);
    }

    /**
     * Adds the nomination count to the list query
     *
     * @param phpws2\Database\DB $db
     * @param phpws2\Database\Table $table
     */
    private static function addNominationCount($db, $table)
    {
        $nominationTable = $db->addTable('award_nomination');
        $nominationId = $nominationTable->getField('id');
        $count = "count(distinct($nominationId))";

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

    /**
     * Formats the date in the listing query.
     * @param phpws2\Database\DB $db
     * @param phpws2\Database\Table $table
     * @param string $format
     */
    private static function formatDates($db, $table, $format = null)
    {
        if (is_string($format)) {
            $format = is_string($options['dateFormat']);
        } else {
            $format = '%l:%i %p, %b %e, %Y';
        }

        $startDateExpression = $db->getExpression('FROM_UNIXTIME(' . $table->getField('startDate') . ', "' . $format . '")', 'formatStartDate');
        $endDateExpression = $db->getExpression('FROM_UNIXTIME(' . $table->getField('endDate') . ', "' . $format . '")', 'formatEndDate');
        $table->addField($startDateExpression);
        $table->addField($endDateExpression);
    }

}
