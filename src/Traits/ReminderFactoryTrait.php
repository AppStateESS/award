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

namespace award\Traits;

use phpws2\Database;

trait ReminderFactoryTrait
{

    /**
     * Requires AbstractFactory
     * @param int $resourceId
     */
    public static function stampReminder(int $resourceId)
    {
        extract(self::getDBWithTable());
        $exp = new Database\Expression('now()');
        $table->addFieldConditional('id', $resourceId);
        $table->addValue('lastReminder', $exp);
        $db->update();
    }

}
