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

use Canopy\Request;
use award\AbstractClass\AbstractController;
use award\View\AwardView;
use award\Factory\AwardFactory;

class Award extends AbstractController
{

    protected function viewHtml()
    {
        $this->idRequired();
        $award = AwardFactory::build($this->id);
        if ($award === false) {
            throw \award\Exception\ResourceNotFound;
        }
        return AwardView::view($award);
    }

}
