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
use phpws2\Database;
use Canopy\Request;
use award\Exception\ResourceNotFound;

class AwardFactory extends AbstractFactory
{

    /**
     * Initiates a Award Resource. If the $id is passed, a retrieval
     * from the database is attempted.
     * @param int $id
     * @return award\Resource\Award
     */
    public static function build(int $id = 0): Award
    {
        $award = new Award;
        if ($id) {
            $result = self::load($award, $id);
            if (!$result) {
                throw new ResourceNotFound($id);
            }
        }
        return $award;
    }

    /**
     * Flips the deleted flag on the Award resource and saves it.
     * @param int $awardId
     */
    public static function delete(int $awardId)
    {
        $award = self::build($awardId);
        $award->setDeleted(true);
        self::save($award);
    }

    public static function getList(array $options = [])
    {
        $db = self::getDB();
        $awardTbl = $db->addTable('award_award');

        if (!empty($options['titleOnly'])) {
            $awardTbl->addField('id');
            $awardTbl->addField('title');
        }

        if (!empty($options['orderBy']) && !empty($options['orderDir'])) {
            $awardTbl->addOrderBy($options['orderBy'], $options['orderDir']);
        }

        if (!empty($options['deletedOnly'])) {
            $awardTbl->addFieldConditional('deleted', 1);
        } else {
            $awardTbl->addFieldConditional('deleted', 0);
        }

        $result = $db->select();
        return self::enforceBooleanValues($result, 'award\Resource\Award');
    }

    /**
     * Parses the Request for post values to fill an award object.
     * @param Request $request
     */
    public static function post(Request $request)
    {
        $award = self::build();
        // New awards are deactivated by default.
        $award->setActive(false);

        $award->setApprovalRequired($request->pullPostBoolean('approvalRequired'));
        $award->setCreditNominator($request->pullPostBoolean('creditNominator'));
        $award->setCycleTerm($request->pullPostString('cycleTerm'));
        $award->setDefaultVoteType($request->pullPostString('defaultVoteType', true) ?? AWARD_DEFAULT_VOTE_TYPE);
        $award->setDescription($request->pullPostString('description'));
        $award->setJudgeMethod($request->pullPostInteger('judgeMethod'));
        $award->setNominationReasonRequired($request->pullPostBoolean('nominationReasonRequired'));
        $award->setParticipantId($request->pullPostInteger('participantId'));
        $award->setPublicView($request->pullPostBoolean('publicView'));
        $award->setReferenceReasonRequired($request->pullPostBoolean('referenceReasonRequired'));
        $award->setReferencesRequired($request->pullPostInteger('referencesRequired'));
        $award->setSelfNominate($request->pullPostBoolean('selfNominate'));
        $award->setTitle($request->pullPostString('title'));
        $award->setTipNominated($request->pullPostBoolean('tipNominated'));
        $award->setWinnerAmount($request->pullPostInteger('winnerAmount'));
        return $award;
    }

    /**
     * Parses the Request for put values to fill an award object.
     * The active parameter is not set in the put.
     * @param Request $request
     */
    public static function put(Request $request, int $id)
    {
        $award = self::build($id);

        $award->setApprovalRequired($request->pullPutBoolean('approvalRequired'));
        $award->setCreditNominator($request->pullPutBoolean('creditNominator'));
        $award->setCycleTerm($request->pullPutString('cycleTerm'));
        $award->setDefaultVoteType($request->pullPutString('defaultVoteType', true) ?? AWARD_DEFAULT_VOTE_TYPE);
        $award->setDescription($request->pullPutString('description'));
        $award->setJudgeMethod($request->pullPutInteger('judgeMethod'));
        $award->setNominationReasonRequired($request->pullPutBoolean('nominationReasonRequired'));
        $award->setParticipantId($request->pullPutInteger('participantId'));
        $award->setPublicView($request->pullPutBoolean('publicView'));
        $award->setReferenceReasonRequired($request->pullPutBoolean('referenceReasonRequired'));
        $award->setReferencesRequired($request->pullPutInteger('referencesRequired'));
        $award->setSelfNominate($request->pullPutBoolean('selfNominate'));
        $award->setTitle($request->pullPutString('title'));
        $award->setTipNominated($request->pullPutBoolean('tipNominated'));
        $award->setWinnerAmount($request->pullPutInteger('winnerAmount'));
        return $award;
    }

    /**
     * Sets the currentCycleId of an Award to the passed in Cycle resource.
     * @param \award\Resource\Cycle $cycle
     */
    public static function setCurrentCycle(Cycle $cycle)
    {
        $award = self::build($cycle->awardId);
        $award->setCurrentCycleId($cycle->id);
        self::save($award);
    }

}
