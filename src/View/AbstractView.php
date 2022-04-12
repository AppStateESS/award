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

use phpws2\Template;

class AbstractView
{

    public static function getTemplate(string $templateFile, array $values, bool $css = false)
    {
        if ($css) {
            $cssFile = "css/{$templateFile}.css";
            \Layout::addStyle('award', $cssFile);
        }
        $values['sourceHttp'] = PHPWS_SOURCE_HTTP;
        $values['homeHttp'] = PHPWS_HOME_HTTP;
        $values['imageHttp'] = PHPWS_HOME_HTTP . 'mod/award/img/';
        $values['moduleCSS'] = PHPWS_HOME_HTTP . 'mod/award/css/';
        $template = new Template($values);
        $template->setModuleTemplate('award', $templateFile . '.html');
        return $template->get();
    }

}
