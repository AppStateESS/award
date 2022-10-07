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
use award\Resource\Award;
use award\Resource\Cycle;
use award\Resource\Nomination;
use phpws2\Database;
use Canopy\Request;
use award\Exception\ResourceNotFound;

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

    public static function getByNominator(int $nominatorId, int $cycleId)
    {
        extract(self::getDBWithTable());
        $table->addFieldConditional('nominatorId', $nominatorId);
        $table->addFieldConditional('cycleId', $cycleId);
        $row = $db->selectOneRow();
        if ($row == false) {
            return false;
        }
        $nomination = NominationFactory::build();
        $nomination->setValues($row);
        return $nomination;
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

    public static function listing(array $options = [])
    {
        extract(self::getDBWithTable());
        return $db->select();
    }

    /**
     * TODO Rename this
     * @param int $participantId
     * @param int $cycleId
     * @param string $reasonText
     * @throws \Exception
     */
    public static function nominateParticipantText(int $participantId, int $cycleId, string $reasonText)
    {
        if (empty($reasonText)) {
            throw new \Exception('missing reason text');
        }
        $nomination = self::build();
        $participant = ParticipantFactory::build($participantId);
        $cycle = CycleFactory::build($cycleId);
        $award = AwardFactory::build($cycle->awardId);

        ParticipantFactory::canBeNominated($participant, $cycle, $award);

        $nomination->setParticipantId($participant->id)->
            setAwardId($award->id)->
            setCycleId($cycle->id)->
            setReasonText($reasonText)->
            setReasonComplete(true);

        self::save($nomination);
    }

}
