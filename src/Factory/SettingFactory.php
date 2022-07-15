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

namespace award\Factory;

class SettingFactory
{

    public static function getEnabledAuthenticators()
    {
        $authenticators = \phpws2\Settings::get('award', 'enabledAuthenticators');
        return explode(',', $authenticators);
    }

    public static function addEnabledAuthenticators(string $filename)
    {
        $authenticators = self::getEnabledAuthenticators();
        if (array_search($filename, $authenticators) === false) {
            $authenticators[] = $filename;
            self::saveAuthenticators($authenticators);
        }
    }

    public static function removeEnabledAuthenticators(string $filename)
    {
        $authenticators = self::getEnabledAuthenticators();
        $key = array_search($filename, $authenticators);
        if ($key !== false) {
            unset($authenticators[$key]);
            self::saveAuthenticators($authenticators);
        }
    }

    private static function saveAuthenticators(array $authenticators)
    {
        \phpws2\Settings::set('award', 'enabledAuthenticators', implode(',', $authenticators));
    }

}
