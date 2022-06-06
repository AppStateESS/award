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

require_once PHPWS_SOURCE_DIR . 'mod/award/config/system.php';

class AbstractFactory
{

    /**
     * Receives a stack of rows and compares value types against the resourceClass.
     * @param array $rows
     * @param string $resourceClass
     */
    public static function enforceBooleanValues(array $rows, string $resourceClass)
    {
        if (empty($rows)) {
            return [];
        }
        $reflection = new \ReflectionClass($resourceClass);
        $properties = $reflection->getProperties();
        $booleanList = [];
        foreach ($properties as $property) {
            $propType = $property->getType()->getName();
            if ($propType === 'bool') {
                $booleanList[] = $property->name;
            }
        }
        $boolIt = function (&$param, $key, $booleanList) {
            if (in_array($key, $booleanList)) {
                $param = (bool) $param;
            }
        };
        foreach ($rows as $key => $row) {
            array_walk($row, $boolIt, $booleanList);
            $rows[$key] = $row;
        }

        return $rows;
    }

    /**
     * Returns a DB object with emulation turned off so we get
     * properly typed values.
     * @return phpws2\Database\DB
     */
    public static function getDB(): \phpws2\Database\DB
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
        $db->begin();
        $table = $db->addTable($resource->getTableName());
        $table->addValueArray($values);
        if ($id) {
            $table->addFieldConditional('id', $id);
            $db->update();
            $db->commit();
        } else {
            $db->insert();
            $last_id = (int) $table->getLastId();
            $resource->setId($last_id);
            $db->commit();
        }
        return $resource;
    }

}
