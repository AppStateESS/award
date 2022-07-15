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

class SettingView extends AbstractView
{

    public static function dashboard()
    {
        $params['menu'] = self::menu('setting');
        $params['script'] = self::scriptView('Setting');
        return self::getTemplate('Admin/AdminForm', $params);
    }

}
