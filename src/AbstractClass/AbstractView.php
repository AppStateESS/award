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

namespace award\AbstractClass;

use phpws2\Template;

class AbstractView
{

    const directory = PHPWS_SOURCE_DIR . 'mod/award/';
    const http = PHPWS_SOURCE_HTTP . 'mod/award/';

    public static function getTemplate(string $templateFile, array $values = [], bool $css = false)
    {
        if ($css) {
            $cssFile = "css/{$templateFile}.css";
            \Layout::addStyle('award', $cssFile);
        }
        $values['siteName'] = \Layout::getPageTitle(true);
        $values['sourceHttp'] = PHPWS_SOURCE_HTTP;
        $values['homeHttp'] = PHPWS_HOME_HTTP;
        $values['imageHttp'] = PHPWS_HOME_HTTP . 'mod/award/img/';
        $values['moduleCSS'] = PHPWS_HOME_HTTP . 'mod/award/css/';
        $template = new Template($values);
        $template->setModuleTemplate('award', $templateFile . '.html');
        return $template->get();
    }

    protected static function getDirectory()
    {
        return self::directory;
    }

    protected static function getHttp()
    {
        return self::http;
    }

    private static function addScriptVars($vars)
    {
        if (empty($vars)) {
            return null;
        }
        foreach ($vars as $key => $value) {
            $varList[] = "const $key = " . json_encode($value, JSON_NUMERIC_CHECK) . ';';
        }
        return '<script type="text/javascript">' . implode('', $varList) . '</script>';
    }

    public static function errorPage()
    {
        $siteContactEmail = \phpws2\Settings::get('award', 'siteContactEmail');
        return self::getTemplate('User/Error', ['contactEmail' => $siteContactEmail]);
    }

    public static function centerCard(string $title, string $content)
    {
        return self::getTemplate('CenterCard', ['title' => $title, 'content' => $content]);
    }

    protected static function getScript($scriptName)
    {
        $jsDirectory = self::getHttp() . 'javascript/';
        if (AWARD_SYSTEM_SETTINGS['productionMode']) {
            $path = $jsDirectory . 'build/' . self::getAssetPath($scriptName);
        } else {
            $path = "{$jsDirectory}dev/$scriptName.js";
        }
        $script = "<script type='text/javascript' src='$path'></script>";
        return $script;
    }

    protected static function getAssetPath($scriptName)
    {
        if (!is_file(self::getDirectory() . 'assets.json')) {
            exit('Missing assets.json file. Run "npm run build" in the award directory.');
        }
        $jsonRaw = file_get_contents(self::getDirectory() . 'assets.json');
        $json = json_decode($jsonRaw, true);
        if (!isset($json[$scriptName]['js'])) {
            throw new \Exception('Script file not found among assets.');
        }
        return $json[$scriptName]['js'];
    }

    protected static function menu($active)
    {
        $params = ['active' => $active];
        return self::getTemplate('Admin/Menu', $params);
    }

    /**
     *
     * @staticvar boolean $vendor_included
     * @param string $view_name
     * @param boolean $add_anchor
     * @param array $vars
     * @return string
     */
    public static function scriptView($view_name, $vars = null)
    {
        static $vendor_included = false;
        if (!$vendor_included) {
            $script[] = self::getScript('vendor');
            $vendor_included = true;
        }
        if (!empty($vars)) {
            $script[] = self::addScriptVars($vars);
        }
        $script[] = self::getScript($view_name);
        $react = implode("\n", $script);
        \Layout::addJSHeader($react);
        $content = <<<EOF
<div id="$view_name"><p>Loading. Please wait.</p></div>
EOF;
        return $content;
    }

}
