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

use award\Resource\Participant;
use award\AbstractClass\AbstractFactory;
use award\View\EmailView;
use phpws2\Database;

class JudgeFactory extends AbstractFactory
{

    protected static string $resourceClassName = 'award\Resource\Judge';
    protected static string $table = 'award_judge';

    public static function create(int $cycleId, int $participantId)
    {
        $judge = JudgeFactory::build();
        $judge->cycleId = $cycleId;
        $judge->participantId = $participantId;
        JudgeFactory::save($judge);
    }

    public static function listing(array $options = [])
    {
        extract(self::getDBWithTable());
        if (!empty($options['cycleId'])) {
            $table->addFieldConditional('cycleId', $options['cycleId']);
        }
        return $db->select();
    }

}
