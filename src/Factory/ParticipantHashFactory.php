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

class ParticipantHashFactory extends AbstractFactory
{

    protected static string $table = 'award_participant_hash';

    /**
     * Creates a request hash for a participant and returns the hash string.
     *
     * @param int $participantId
     * @param int $hours How many hours in advance to set the timeout.
     * @return string
     */
    public static function create(int $participantId, int $hours = AWARD_HASH_DEFAULT_TIMER_HOURS)
    {
        self::remove($participantId);
        /**
         * @var \phpws2\Database\DB $db
         * @var \phpws2\Database\Table $table
         */
        extract(self::getDBWithTable());
        $timeout = time() + ($hours * 3600);
        $hash = sha1((string) (rand() + time()));
        $table->addValue('participantId', $participantId);
        $table->addValue('hash', $hash);
        $table->addValue('timeout', $timeout);
        $table->insert();
        return $hash;
    }

    /**
     * Returns the hash for the current participant id that is under
     * the timeout deadline.
     * @param int $participantId
     * @return type
     */
    public static function get(int $participantId)
    {
        /**
         * @var \phpws2\Database\DB $db
         * @var \phpws2\Database\Table $table
         */
        extract(self::getDBWithTable());
        $table->addField('hash');
        $table->addFieldConditional('participantId', $participantId);
        $table->addFieldConditional('timeout', time(), '>=');
        return $db->selectColumn();
    }

    public static function isValid(int $participantId, string $hash): bool
    {
        return self::get($participantId) === $hash;
    }

    public static function remove(int $participantId)
    {
        /**
         * @var \phpws2\Database\DB $db
         * @var \phpws2\Database\Table $table
         */
        extract(self::getDBWithTable());
        $table->addFieldConditional('participantId', $participantId);
        $db->delete();
    }

    /**
     * Removes all hashes that have timed out.
     */
    public static function removeTimedOut()
    {
        /**
         * @var \phpws2\Database\DB $db
         * @var \phpws2\Database\Table $table
         */
        extract(self::getDBWithTable());
        $table->addFieldConditional('timeout', time(), '<');
        $db->delete();
    }

}
