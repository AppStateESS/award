<?php

/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
use phpws2\Database;

function award_uninstall(&$content)
{
    $sqlFile = PHPWS_SOURCE_DIR . 'mod/award/boost/uninstall.sql';
    $sql = file_get_contents($sqlFile);
    $db = Database::getDB();
    $db->exec($sql);
    $content[] = 'Uninstalled tables.';
    return true;
}
