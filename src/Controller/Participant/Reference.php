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

namespace award\Controller\Participant;

use Canopy\Request;
use award\AbstractClass\AbstractController;
use award\View\ReferenceView;
use award\Factory\ParticipantFactory;
use award\Factory\ReferenceFactory;
use award\Factory\EmailFactory;
use award\Factory\Authenticate;

class Reference extends AbstractController
{

    protected function listJson(Request $request)
    {
        $options = [];
        $options['nominationId'] = $request->pullGetInteger('nominationId');
        $options['includeParticipant'] = true;
        return ReferenceFactory::listing($options);
    }

}
