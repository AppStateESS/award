<?php

/**
 * MIT License
 * Copyright (c) 2019 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\Controller;

use award\View\AwardView;

define('AWARD_FRIENDLY_MESSAGE', 'Server error. Could not complete action');

class FriendlyErrorController extends \phpws2\Http\Controller
{

    public function execute(\Canopy\Request $request)
    {
        if ($request->isAjax()) {
            throw new \Exception(AWARD_FRIENDLY_MESSAGE);
        }
        return parent::execute($request);
    }

    public function get(\Canopy\Request $request)
    {
        $view = new \phpws2\View\HtmlView(AwardView::errorPage());
        $response = new \Canopy\Response($view);
        return $response;
    }

}
