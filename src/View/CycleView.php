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

namespace award\View;

use award\AbstractClass\AbstractView;

class CycleView extends AbstractView
{

    /**
     * Returns a listing of cycles that need attention (vote approval, nomination ending)
     */
    public static function noteworthy(): string
    {
        return 'Noteworthy item listing';
    }

}
