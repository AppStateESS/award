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
use award\Factory\NominationFactory;
use Canopy\Request;

class Nomination extends AbstractController
{

    protected function listJson(Request $request)
    {
        return NominationFactory::listing(['cycleId' => $request->pullGetInteger('cycleId'), 'includeNominated' => true]);
    }

    protected function needsApprovalJson()
    {
        return NominationFactory::listing(['includeNominated' => true, 'includeAward' => true, 'includeCycle' => true, 'unapproveOnly' => true, 'includeNominator' => true]);
    }

}
