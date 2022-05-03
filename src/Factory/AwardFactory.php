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
use phpws2\Database;
use Canopy\Request;

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
        if (!$id) {
            return $award;
        } else {
            return self::load($award, $id);
        }
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
        $award->setCreditNominator($request->pullPostBoolean('creditNominator'));
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

}
