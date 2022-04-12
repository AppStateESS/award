<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award;

abstract class AbstractResource extends AbstractConstruct
{

    /**
     * Primary key for Resources
     * @var int
     */
    protected int $id;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns an array of object properties.
     * @return array
     */
    public function getProperties(): array
    {
        $reflection = new \ReflectionClass(get_called_class());
        $properties = $reflection->getProperties();
        foreach ($properties as $p) {
            $list[] = $p->name;
        }
        return $list;
    }

    public function getValues()
    {
        $properties = $this->getProperties();
        foreach ($properties as $p) {
            $list[$p] = self::getByMethod($p);
        }
        return $list;
    }

    public function setValues(array $values)
    {
        $properties = $this->getProperties();
        foreach ($properties as $p) {
            $list[$p] = self::setByMethod($values[$p]);
        }
        return $list;
    }

}
