<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

final class FieldMapList
{
    private FieldMapCollection $fields;
    private FieldMapCollection $fieldsToJoin;

    public function __construct(array $fieldMap = [])
    {
        $this->fields = new FieldMapCollection();
        $this->fieldsToJoin = new FieldMapCollection();

        foreach ($fieldMap as $alias => $map) {
            $field = new FieldMap($alias, $map);
            $this->fields->set($field->alias(), $field);

            if ($field->canJoin()) {
                $this->fieldsToJoin->set($field->alias(), $field);
            }
        }
    }

    public function hasField(string $name): bool
    {
        return $this->fields->containsKey($name);
    }

    public function canSelect(Field $field): bool
    {
        $fieldMap = $this->getFieldMap($field);
        return (null !== $fieldMap) && $fieldMap->canSelect();
    }

    public function canFilter(FilterField $filter): bool
    {
        $fieldMap = $this->getFieldMap($filter);
        return (null !== $fieldMap) && $fieldMap->canFilter();
    }

    public function canSort(SortField $sort): bool
    {
        $fieldMap = $this->getFieldMap($sort);
        return (null !== $fieldMap) && $fieldMap->canSort();
    }

    private function getFieldMap(Field $field): ?FieldMap
    {
        if (!$this->fields->containsKey($field->alias())) {
            return null;
        }
        /** @var FieldMap $fieldMap */
        $fieldMap = $this->fields[$field->alias()];
        $field->mapping($fieldMap);
        return $fieldMap;
    }

    public function getJoins(): FieldCollection
    {
        $joinFields = new FieldCollection();
        if ($this->fieldsToJoin->isEmpty()) {
            return $joinFields;
        }

        foreach ($this->fieldsToJoin as $fieldMap) {
            $joinFields[] = Field::fromMap($fieldMap);
        }

        return $joinFields;
    }
}
