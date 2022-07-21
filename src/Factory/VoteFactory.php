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

class VoteFactory extends \award\AbstractClass\AbstractFactory
{

    protected static string $table = 'award_vote';
    protected static string $resourceClassName = 'award\Resource\Vote';

    public static function getTypeList()
    {
        include PHPWS_SOURCE_DIR . 'mod/award/config/votetypes.php';
        return $votetypes;
    }

}
