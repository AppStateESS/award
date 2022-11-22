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

use award\Factory\EmailFactory;
use award\Factory\ReferenceFactory;
use award\AbstractClass\AbstractController;
use Canopy\Request;

class Reference extends AbstractController
{

    protected function listJson(Request $request)
    {
        return ReferenceFactory::listing([
                'nominationId' => $request->pullGetInteger('nominationId'),
                'includeParticipant' => true,
                'includeNominated' => true,
                'includeNominator' => true]);
    }

    /**
     * Reminds a reference they need to complete their reference reason.
     */
    protected function remindJson()
    {
        $reference = ReferenceFactory::build($this->id);
        EmailFactory::referenceReminder($reference);
        $reference->stampLastReminder();
        ReferenceFactory::save($reference);
        return ['success' => true];
    }

}
