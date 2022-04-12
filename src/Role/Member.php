<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\Role;

class Participant extends Base
{

    public $memberId = 0;

    public function isParticipant()
    {
        return true;
    }

}
