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
use award\Resource\Cycle;
use phpws2\Database;
use Canopy\Request;

class CycleFactory extends AbstractFactory
{

    /**
     * Initiates a Cycle Resource. If the $id is passed, a retrieval
     * from the database is attempted.
     * @param int $id
     * @return award\Resource\Cycle
     */
    public static function build(int $id = 0): Cycle
    {
        $cycle = new Cycle;

        if (!$id) {
            return $cycle;
        } else {
            return self::load($cycle, $id);
        }
    }

    public static function list(array $options = [])
    {
        $db = parent::getDB();
        $tbl = $db->addTable('award_cycle');
        if (!empty($options['awardId'])) {
            $tbl->addFieldConditional('awardId', (int) $options['awardId']);
        }

        $tbl->addOrderBy('startDate', 'desc');

        return $db->select();
    }

    /**
     * Copies the post information to cycle object.
     * Does not set currentActive or voteAllowed. Both default to false.
     * @param Request $request
     */
    public static function post(Request $request)
    {
        $cycle = self::build();

        $cycle->setAwardId($request->pullPostInteger('awardId'));
        $cycle->setAwardMonth($request->pullPostInteger('awardMonth'));
        $cycle->setAwardYear($request->pullPostInteger('awardYear'));
        $cycle->setEndDate($request->pullPostInteger('endDate'));
        $cycle->setStartDate($request->pullPostInteger('startDate'));
        $cycle->setTerm($request->pullPostString('term'));
        $cycle->setVoteType($request->pullPostString('voteType'));
        return $cycle;
    }

}
