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

}
