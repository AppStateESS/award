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

namespace award\Exception;

class CycleNotStarted extends \Exception
{

    public function __construct(int $cycleId)
    {
        parent::__construct("cycle #$cycleId does not yet accept nominations");
    }

}
