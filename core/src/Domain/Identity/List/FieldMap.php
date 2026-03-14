<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

use App\Domain\Identity\List\Exception\InvalidFilterMapException;

final class FieldMap
{
    private string $alias;
    private string $fieldName;
    private FilterType $type;
    private ValueKind $kind;
    private FieldOptions $options;

    /**
     * @param int|string $alias
     * @param array|string|int|FilterType $map
     */
    public function __construct(int|string $alias, mixed $map)
    {
        $this->alias = '';
        $this->fieldName = '';
        $this->type = FilterType::WILDCARD;
        $this->kind = ValueKind::STRING;
        $this->options = new FieldOptions();

        if (is_string($alias)) {
            $this->alias = $alias;
        }
        $this->parseMap($map);

        if (empty($this->fieldName) && empty($this->alias)) {
            throw new InvalidFilterMapException();
        }
        else if (empty($this->fieldName)) {
            $this->fieldName = $this->alias;
        }
        else if (empty($this->alias)) {
            $this->alias = $this->fieldName;
        }
    }

    /**
     * @param array|string|int|FilterType $map
     */
    private function parseMap(mixed $map): void
    {
        if (is_array($map)) {
            foreach ($map as $item) {
                $this->parseItem($item);
            }
        } else {
            $this->parseItem($map);
        }
    }

    /**
     * @param string|int|FilterType $item
     */
    private function parseItem(mixed $item): void
    {
        if (is_string($item) && empty($this->fieldName)) {
            $this->fieldName = $item;
            return;
        }

        if ($item instanceof FilterType) {
            $this->type = $item;
            return;
        }

        if ($item instanceof ValueKind) {
             $this->kind = $item;
             return;
        }

        if ($item instanceof FieldOption) {
            $this->options->add($item);
        }
    }

    public function alias(): string
    {
        return $this->alias;
    }

    public function fieldName(): string
    {
        return $this->fieldName;
    }

    public function type(): FilterType
    {
        return $this->type;
    }

    public function kind(): ValueKind
    {
        return $this->kind;
    }

    public function options(): FieldOptions
    {
        return $this->options;
    }

    public function canSelect(): bool
    {
        return $this->options->canSelect();
    }

    public function canFilter(): bool
    {
        return $this->options->canFilter();
    }

    public function canSort(): bool
    {
        return $this->options->canSort();
    }

    public function canExclude(): bool
    {
        return $this->options->canExclude();
    }

    public function canJoin(): bool
    {
        return $this->options->canJoin();
    }
}
