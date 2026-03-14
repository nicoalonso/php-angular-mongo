<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

final class FieldOptions
{
    public function __construct(
        private bool $select = true,
        private bool $filter = true,
        private bool $sort = true,
        private bool $exclude = false,
        private bool $join = false,
    ) {}

    public function add(FieldOption $option): void
    {
        match ($option) {
            FieldOption::NO_SELECT => $this->select = false,
            FieldOption::NO_FILTER => $this->filter = false,
            FieldOption::NO_SORT => $this->sort = false,
            FieldOption::EXCLUDE => $this->exclude = true,
            FieldOption::JOIN => $this->join = true,
        };
    }

    public function canSelect(): bool
    {
        return $this->select;
    }

    public function canFilter(): bool
    {
        return $this->filter;
    }

    public function canSort(): bool
    {
        return $this->sort;
    }

    public function canExclude(): bool
    {
        return $this->exclude;
    }

    public function canJoin(): bool
    {
        return $this->join;
    }
}
