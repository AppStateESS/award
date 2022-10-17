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
use award\Exception\CannotNominateJudge;
use award\Exception\CycleComplete;
use award\Exception\CycleEndDatePassed;
use award\Exception\ParticipantPrivilegeMissing;
use award\Exception\ResourceNotFound;
use award\Resource\Award;
use award\Resource\Cycle;
use award\Resource\Nomination;
use award\Resource\Participant;
use Canopy\Request;
use phpws2\Database;

class NominationFactory extends AbstractFactory
{

    protected static string $table = 'award_nomination';
    protected static string $resourceClassName = 'award\Resource\Nomination';

    public static function canComplete(Award $award, Nomination $nomination)
    {
        return (!$award->nominationReasonRequired || ($award->nominationReasonRequired && $nomination->reasonComplete)) &&
            (!$award->referencesRequired || ($award->referencesRequired && $nomination->referencesSelected));
    }

    public static function create(int $nominatorId, int $participantId, int $awardId, int $cycleId)
    {

        $nomination = self::build();
        $nomination->setAwardId($awardId)->
            setCycleId($cycleId)->
            setParticipantId($participantId)->
            setNominatorId($nominatorId);
        self::save($nomination);
        return $nomination;
    }

    public static function nominationAllowed(Participant $nominator, Nomination $nomination)
    {
        //$participant = ParticipantFactory::build($nomination->participantId);
        if (ParticipantFactory::currentIsJudge($nomination->cycleId)) {
            throw new CannotNominateJudge;
        }

        if ($nomination->nominatorId !== $nominator->id) {
            throw new ParticipantPrivilegeMissing;
        }
    }

    /**
     * Returns a nomination resource object if the nomination exists, false otherwise.
     * @param int $nominatorId
     * @param int $participantId
     * @param int $cycleId
     * @return boolean | award\Resource\Nomination
     */
    public static function getByNominator(int $nominatorId, int $participantId, int $cycleId)
    {
        extract(self::getDBWithTable());
        $table->addFieldConditional('nominatorId', $nominatorId);
        $table->addFieldConditional('participantId', $participantId);
        $table->addFieldConditional('cycleId', $cycleId);
        return self::convertRowToResource($db->selectOneRow());
    }

    public static function getCycleCount(int $cycleId)
    {
        extract(self::getDBWithTable());
        $table->addFieldConditional('cycleId', $cycleId);
        $count = "count({$table->getField('id')})";
        $expression = new Database\Expression($count, 'nominations');
        $table->addField($expression);
        return $db->selectColumn();
    }

    public static function getByParticipant(int $participantId, int $cycleId)
    {
        extract(self::getDBWithTable());
        $table->addFieldConditional('participantId', $participantId);
        $table->addFieldConditional('cycleId', $cycleId);
        return self::convertRowToResource($db->selectOneRow());
    }

    /**
     * Options:
     * - cycleId            integer Only returns nomination from a cycle.
     * - nominatorId        integer Only returns nominations created by Participant by
     *                              nominatorId
     * - includeCompleted   boolean Return completed nominations. Default to not do so.
     * - includeNominated   boolean If true, add participant info to nomination.
     * - includeAward       boolean If true, include award title.
     * - includeCycle       boolean If true, include cycle information.
     */
    public static function listing(array $options = [])
    {
        extract(self::getDBWithTable());

        if (!empty($options['cycleId'])) {
            $table->addFieldConditional('cycleId', $options['cycleId']);
        }

        if (!empty($options['nominatorId'])) {
            $table->addFieldConditional('nominatorId', $options['nominatorId']);
        }

        if (empty($options['includeCompleted'])) {
            $table->addFieldConditional('completed', 0);
        }

        if (!empty($options['includeNominated'])) {
            self::includeNominated($db, $table);
        }

        if (!empty($options['includeAward'])) {
            self::includeAward($db, $table);
        }
        if (!empty($options['includeCycle'])) {
            self::includeCycle($db, $table);
        }
        return $db->select();
    }

    private static function includeAward($db, $table)
    {
        $awardTable = $db->addTable('award_award');
        $awardTable->addField('title', 'awardTitle');

        $db->joinResources($table, $awardTable, new Database\Conditional($db, $table->getField('awardId'), $awardTable->getField('id'), '=', 'left'));
    }

    private static function includeCycle($db, $table)
    {
        $cycleTable = $db->addTable('award_cycle');
        $cycleTable->addField('awardMonth');
        $cycleTable->addField('awardYear');
        $cycleTable->addField('endDate');
        $cycleTable->addField('term');
        $cycleTable->addField('completed');

        $db->joinResources($table, $cycleTable, new Database\Conditional($db, $table->getField('cycleId'), $cycleTable->getField('id'), '=', 'left'));
    }

    private static function includeNominated($db, $table)
    {
        $participantTable = $db->addTable('award_participant');
        $participantTable->addField('firstName');
        $participantTable->addField('lastName');
        $participantTable->addField('email');

        $db->joinResources($table, $participantTable, new Database\Conditional($db, $table->getField('participantId'), $participantTable->getField('id'), '=', 'left'));
    }

}
