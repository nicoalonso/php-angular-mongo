<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

class Field
{
    protected string $alias;
    protected string $name;

    public function __construct(string $name)
    {
        $this->alias = $name;
        $this->name = $name;
    }

    public static function fromMap(FieldMap $fieldMap): self
    {
        $field = new self($fieldMap->alias());
        $field->mapping($fieldMap);
        return $field;
    }

    public function mapping(FieldMap $fieldMap): void
    {
        $this->name = $fieldMap->fieldName();
    }

    public function lookup(string $name): void
    {
        $this->name = $name;
    }

    public function alias(): string
    {
        return $this->alias;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function is(string $name): bool
    {
        return $this->name == $name;
    }
}
