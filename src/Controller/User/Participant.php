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

namespace award\Controller\User;

use award\Controller\AbstractController;
use award\View\ParticipantView;

class Participant extends AbstractController
{

    protected function createAccountHtml()
    {
        return ParticipantView::createAccount();
    }

    protected function signinHtml()
    {
        return ParticipantView::signin();
    }

}
