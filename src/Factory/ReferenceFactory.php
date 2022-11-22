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
use award\Resource\Participant;
use phpws2\Database;
use award\Traits\ReminderFactoryTrait;

class ReferenceFactory extends AbstractFactory
{

    use ReminderFactoryTrait;

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
     *
     * @param Reference $reference
     * @return Returns TRUE if participant recommended reference.
     */
    public static function isNominatorReference(Reference $reference, Participant $participant)
    {
        $nomination = NominationFactory::build($reference->nominationId);
        return $nomination->getNominatorId() === $participant->getId();
    }

    /**
     * Options
     * - cycleId            (integer) Return only references associated with this cycle.
     * - nominationId       (integer) Return only references associated with this nomination.
     * - participantIdOnly  (boolean) Return only participant ids.
     * - includeParticipant (boolean) Add participant's email plus first and last name.
     * - includeNominator   (boolean) Add nominator email and name
     * - includeNominated   (boolean) Add nominated email and name
     * - includeAward       (boolean) Add the award title and reference requirements to the row
     * - count              (boolean) Returns the number of references.
     *
     * @param array $options
     * @return type
     */
    public static function listing(array $options = [])
    {
        extract(self::getDBWithTable());

        self::addIdOptions($table, ['cycleId', 'nominationId', 'participantId'], $options);

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
        } else {
            if (!empty($options['includeParticipant'])) {
                self::includeParticipant($db, $table);
            }
            if (!empty($options['includeNominator'])) {
                $nominationTable = $db->addTable('award_nomination', 'nominator', false);
                $db->joinResources($table, $nominationTable, new Database\Conditional($db, $table->getField('nominationId'), $nominationTable->getField('id'), '='));
                self::includeParticipant($db, $nominationTable, 'nominator');
            }
            if (!empty($options['includeNominated'])) {
                $nominationTable = $db->addTable('award_nomination', 'nominated', false);
                $db->joinResources($table, $nominationTable, new Database\Conditional($db, $table->getField('nominationId'), $nominationTable->getField('id'), '='));
                self::includeParticipant($db, $nominationTable, 'nominated');
            }
            if (!empty($options['includeAward'])) {
                $awardTable = $db->addTable('award_award');
                $awardTable->addField('title', 'awardTitle');
                $awardTable->addField('referenceReasonRequired');
                $db->joinResources($table, $awardTable, new Database\Conditional($db, $table->getField('awardId'), $awardTable->getField('id'), '='));
            }
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
