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
use award\Resource\Nomination;
use phpws2\Database;

class ReferenceFactory extends AbstractFactory
{

    protected static string $resourceClassName = 'award\Resource\Reference';
    protected static string $table = 'award_reference';

    /**
     * Creates a new reference.
     * @param int $cycleId
     * @param int $nominationId
     * @param int $participantId
     * @return award\Resource\Reference
     */
    public static function create(int $cycleId, int $nominationId, int $participantId)
    {
        $reference = self::build();
        $reference->setCycleId($cycleId);
        $reference->setNominationId($nominationId);
        $reference->setParticipantId($participantId);
        return self::save($reference);
    }

    /**
     * Options
     * - cycleId (integer) Return only references associated with this cycle.
     * - nominationId (integer) Return only references associated with this nomination.
     * - participantIdOnly (boolean) Return only participant ids.
     * - includeParticipant (boolean) Add participant's email plus first and last name.
     * - count (boolean) Returns the number of references.
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
        } elseif (!empty($options['count'])) {
            $idField = $table->getField('id');
            $countExpression = new Database\Expression("count($idField)");
            $table->addField($countExpression);
            return $db->selectColumn();
        } elseif (!empty($options['includeParticipant'])) {
            $partTable = $db->addTable('award_participant');
            $partTable->addField('firstName');
            $partTable->addField('lastName');
            $partTable->addField('email');
            $db->joinResources($table, $partTable, new Database\Conditional($db, $table->getField('participantId'), $partTable->getField('id'), '='));
        }
        return $db->select();
    }

    /**
     * Returns reference status.
     * Returns TRUE if
     * - The award does not require references, or
     * - The amount of references meets or exceed the award's requirement AND
     *   - the award's reference reason is required AND
     *     each reference as reason text or an associated document.
     *     OR
     *   - the award's reference reason is not required
     * @param Nomination $nomination
     * @return boolean
     */
    public static function nominationReferencesComplete(Nomination $nomination)
    {
        $award = AwardFactory::build($nomination->awardId);
        $references = self::listing(['nominationId' => $nomination->id]);
        if (!$award->referencesRequired) {
            // references are not required, return true
            return true;
        } elseif (count($references) < $award->referencesRequired) {
            // reference count is under the requirement, return false
            return false;
        }

        // Now test reference reasons
        if (!$award->referenceReasonRequired) {
            // reasons are not required, return true
            return true;
        } else {
            // Reasons are required, now check each reference to see
            // if their reason is set
            foreach ($references as $ref) {
                if (empty($ref['reasonText']) && empty($ref['reasonDocument'])) {
                    // both reasonText and reasonDocument are empty, return false
                    return false;
                }
            }
        }
        // references passed the reason check, return true
        return true;
    }

}
