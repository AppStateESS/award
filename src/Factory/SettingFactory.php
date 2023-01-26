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

use phpws2\Settings;

class SettingFactory
{

    public static function addEnabledAuthenticators(string $filename)
    {
        $authenticators = self::getEnabledAuthenticators();
        if (array_search($filename, $authenticators) === false) {
            $authenticators[] = $filename;
            self::saveAuthenticators($authenticators);
        }
    }

    public static function getAll()
    {
        $settings['siteContactName'] = Settings::get('award', 'siteContactName');
        $settings['siteContactEmail'] = Settings::get('award', 'siteContactEmail');
        $settings['trustedDefault'] = Settings::get('award', 'trustedDefault');
        $settings['useWarehouse'] = Settings::get('award', 'useWarehouse');
        return $settings;
    }

    public static function getEnabledAuthenticators()
    {
        $authenticators = Settings::get('award', 'enabledAuthenticators');
        return explode(',', $authenticators);
    }

    /**
     * Returns email contact settings as [contactName, contactEmail]
     * @return array
     */
    public static function getSiteContact()
    {
        $contact['contactName'] = Settings::get('award', 'siteContactName');
        $contact['contactEmail'] = Settings::get('award', 'siteContactEmail');
        return $contact;
    }

    /**
     *
     * @return boolean
     */
    public static function getTrustedDefault()
    {
        return (bool) Settings::get('award', 'trustedDefault');
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

    public static function setTrustedDefault(bool $trusted)
    {
        Settings::set('award', 'trustedDefault', $trusted);
    }

    public static function setUseWarehouse(bool $useWarehouse)
    {
        Settings::set('award', 'useWarehouse', $useWarehouse);
    }

    public static function useWarehouse()
    {
        return (bool) Settings::get('award', 'useWarehouse');
    }

    private static function saveAuthenticators(array $authenticators)
    {
        Settings::set('award', 'enabledAuthenticators', implode(',', $authenticators));
    }

}
