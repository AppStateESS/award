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

use phpws2\Database;

class AbstractFactory
{

    /**
     * Returns a DB object with emulation turned off so we get
     * properly typed values.
     * @return phpws2\Database\DB
     */
    public static function getDB()
    {
        $db = Database::getDB();
        $db::$PDO->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        return $db;
    }

    protected static function load(AbstractResource $resource, int $id)
    {
        $db = self::getDB();
        $tbl = $db->addTable($resource->getTableName());
        $tbl->addFieldConditional('id', $id);
        $result = $db->selectOneRow();
        if (empty($result)) {
            return false;
        } else {
            $resource->setValues($result);
            return $resource;
        }
    }

    public static function save(AbstractResource $resource)
    {
        $id = $resource->getId();
        if ($id) {
            if (method_exists($resource, 'stampUpdated')) {
                $resource->stampUpdated();
            }
        } else {
            if (method_exists($resource, 'stampCreated')) {
                $resource->stampCreated();
                $resource->stampUpdated();
            }
        }
        $values = $resource->getValues();
        unset($values['id']);
        $db = Database::getDB();
        $table = $db->addTable($resource->getTable());
        $table->addValueArray($values);
        if ($id) {
            $db->update();
        } else {
            $db->insert();
        }
        return $resource;
    }

}
