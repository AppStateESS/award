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

namespace award;

use phpws2\Database;

class AbstractFactory
{

    public static function save(AbstractResource $resource)
    {
        $values = $resource->getValues();
        unset($values['id']);
        $db = Database::getDB();
        $table = $db->addTable($resource->getTable());
        $table->addValueArray($values);
        $id = $resource->getId();
        if ($id) {
            return $db->update();
        } else {
            return $db->insert();
        }
    }

}
