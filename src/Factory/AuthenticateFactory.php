<?php

/**
 * This is written strictly for Shibboleth. Should authentication change in some manner it will
 * need to be reworked.
 * @deprecated
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\Factory;

class AuthenticateFactory
{

    const authDirectory = PHPWS_SOURCE_DIR . 'mod/award/src/Authtypes/';

    /**
     * Returns an array of authentication files in the award directory.
     * @return boolean | array
     */
    public static function getAuthtypeList()
    {
        $filtered = self::getFileList();
        if ($filtered === false) {
            return false;
        }
        return self::authenticationInfo($filtered);
    }

    /**
     * Returns an array of sign in buttons based on enabled authentication methods.
     */
    public static function getSignInButtons(): array
    {
        return ['<button>Sign in 1</button>', '<button>Sign In 2</button>'];
    }

    /**
     * Signs out of the $authType received (if applicable).
     * The local authType (i.e. zero) does nothing. ParticipantFactory::signOut()
     * is must be called regardless.
     *
     * @param int $authType
     * @return type
     */
    public static function signOut(int $authType)
    {
        if ($authType === 0) {
            return;
        }
    }

    private static function authenticationInfo(array $filelist)
    {
        $authenticators = SettingFactory::getEnabledAuthenticators();
        $info = [];
        foreach ($filelist as $file) {
            $className = 'award\\Authtypes\\' . str_replace('.php', '', $file);
            require_once self::authDirectory . $file;
            $obj = new $className;
            $info[] = ['filename' => $file, 'title' => $obj->getTitle(), 'enabled' => in_array($file, $authenticators)];
        }
        return $info;
    }

    private static function getFileList()
    {
        $authTypeFiles = scandir(self::authDirectory);
        if (!$authTypeFiles) {
            return false;
        }
        $filtered = [];
        foreach ($authTypeFiles as $filename) {
            if (preg_match('/\.php$/', $filename)) {
                $filtered[] = $filename;
            }
        }
        if (empty($filtered)) {
            return false;
        } else {
            return $filtered;
        }
    }

}
