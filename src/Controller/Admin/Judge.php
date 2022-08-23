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

namespace award\Controller\Admin;

use award\AbstractClass\AbstractController;
//use award\View\JudgeView;
use award\Factory\JudgeFactory;
use award\Factory\ParticipantFactory;
use Canopy\Request;

class Judge extends AbstractController
{

    public static function listJson(Request $request)
    {
        $options = [];
        $options['cycleId'] = $request->pullGetInteger('cycleId', true);
        $options['includeParticipant'] = true;
        return JudgeFactory::listing($options);
    }

}
