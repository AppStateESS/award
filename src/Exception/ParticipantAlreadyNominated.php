<?php

/**
 * MIT License
 * Copyright (c) 2020 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\Exception;

class ParticipantAlreadyNominated extends \Exception
{

    public function __construct()
    {
        parent::__construct('participant already nominated for this cycle');
    }

}
