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

    public static function centerCard(string $title, string $content, string $backgroundColor = 'primary')
    {
        return self::getTemplate('CenterCard', ['title' => $title, 'content' => $content, 'backgroundColor' => $backgroundColor]);
    }

    public static function errorPage()
    {
        $siteContactEmail = \phpws2\Settings::get('award', 'siteContactEmail');
        return self::centerCard('Drat!',
                self::getTemplate('User/Error', ['contactEmail' => $siteContactEmail]), 'danger');
    }

    public static function getFullAwardTitle(\award\Resource\Award $award, \award\Resource\Cycle $cycle)
    {
        $awardTitle = '';
        if ($cycle->term === 'yearly') {
            $awardTitle = $cycle->awardYear . ' ' . $award->title;
        } else {
            $awardTitle = $cycle->awardMonth . ' ' . $award->title;
        }

        if (!preg_match('/ award$/i', $awardTitle)) {
            $awardTitle .= ' award';
        }
        return $awardTitle;
    }

    /**
     * Returns the output of the values applied to the $templateFile. Default values
     * sent to the template include:
     * - siteName: the page title
     * - sourceHttp: hub base url
     * - homeHttp: branch base url
     * - imageHttp: mod/award/img url
     * - moduleCSS: mod/award/css url
     * - contactName: contact name from Settings
     * - contactEmail: email address of site from Settings
     *
     * @param string $templateFile
     * @param array $values
     * @param bool $css
     * @return string
     */
    public static function getTemplate(string $templateFile, array $values = [], bool $css = false)
    {
        if ($css) {
            $cssFile = "css/{$templateFile}.css";
            \Layout::addStyle('award', $cssFile);
        }
        $values = array_merge(\award\Factory\SettingFactory::getSiteContact(), $values);
        $values['siteName'] = \Layout::getPageTitle(true);
        $values['sourceHttp'] = PHPWS_SOURCE_HTTP;
        $values['homeHttp'] = PHPWS_HOME_HTTP;
        $values['imageHttp'] = PHPWS_SOURCE_HTTP . 'mod/award/img/';
        $values['moduleCSS'] = PHPWS_SOURCE_HTTP . 'mod/award/css/';
        $template = new Template($values);
        $template->setModuleTemplate('award', $templateFile . '.html');
        return $template->get();
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
<div id="$view_name">Loading area...</div>
EOF;
        return $content;
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

    protected static function getDirectory()
    {
        return self::directory;
    }

    protected static function getHttp()
    {
        return self::http;
    }

    public static function getLogoutUrl()
    {
        $auth = \Current_User::getAuthorization();
        return $auth->logout_link;
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

    public static function adminMenu($active)
    {
        $params = ['active' => $active, 'logoutUrl' => self::getLogoutUrl()];
        return self::getTemplate('Admin/Menu', $params);
    }

    public static function participantMenu($active)
    {
        // TODO logout needs to use participant logout
        $auth = \Current_User::getAuthorization();
        $params = ['active' => $active, 'logoutUrl' => $auth->logout_link];
        return self::getTemplate('Participant/Menu', $params);
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

}
