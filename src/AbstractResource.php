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
    private string $tableName;

    public function getId()
    {
        return $this->id ?? 0;
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

    public function getTable(): string
    {
        return $this->tableName;
    }

    public function getValues()
    {
        $properties = $this->getProperties();
        foreach ($properties as $p) {
            $list[$p] = self::getByMethod($p);
        }
        return $list;
    }

    /**
     * Sets the primary key of the resource.
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setValues(array $values)
    {
        $properties = $this->getProperties();

        foreach ($properties as $p) {
            $list[$p] = self::setByMethod($p, $values[$p]);
        }
        return $list;
    }

    protected function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

}
