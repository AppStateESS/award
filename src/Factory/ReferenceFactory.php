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

use award\Resource\Reference;
use award\AbstractClass\AbstractFactory;
use award\View\EmailView;
use phpws2\Database;

class ReferenceFactory extends AbstractFactory
{

    protected static string $resourceClassName = 'award\Resource\Reference';
    protected static string $table = 'award_reference';

    /**
     * Options
     * - cycleId (integer) Return only references associated with this cycle.
     * - nominationId (integer) Return only references associated with this nomination.
     * - participantIdOnly (boolean) Return only participant ids.
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

        if (!empty($options['nominationId'])) {
            $table->addFieldConditional('nominationId', $options['nominationId']);
        }

        if (!empty($options['participantIdOnly'])) {
            $ids = [];
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
