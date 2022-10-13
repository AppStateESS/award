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

    public static function errorCheckNomination(Participant $nominator, Nomination $nomination)
    {
        //$participant = ParticipantFactory::build($nomination->participantId);
        $cycle = CycleFactory::build($nomination->cycleId);
        CycleFactory::nominationAllowed($cycle);

        if (ParticipantFactory::currentIsJudge($cycle->id)) {
            throw new CannotNominateJudge;
        }

        if ($nomination->nominatorId !== $nominator->id) {
            throw new ParticipantPrivilegeMissing;
        }
    }

    /**
     * Returns a nomination resource object if the nomination exists, false otherwise.
     * @param int $nominatorId
     * @param int $cycleId
     * @return boolean | award\Resource\Nomination
     */
    public static function getByNominator(int $nominatorId, int $cycleId)
    {
        extract(self::getDBWithTable());
        $table->addFieldConditional('nominatorId', $nominatorId);
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
     * - includeNominated   boolean If true, add participant info to nomination.
     */
    public static function listing(array $options = [])
    {
        extract(self::getDBWithTable());

        if (!empty($options['cycleId'])) {
            $table->addFieldConditional('cycleId', $options['cycleId']);
        }

        if (!empty($options['includeNominated'])) {
            self::includeNominated($db, $table);
        }
        return $db->select();
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
