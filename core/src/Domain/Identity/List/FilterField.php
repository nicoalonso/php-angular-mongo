<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

final class FilterField extends Field
{
    private FilterType $type;
    private ValueKind $kind;
    private string $input;
    private mixed $value;
    private ?FilterVisitor $visitor;

    public function __construct(
        string      $name,
        string      $input,
        ?FilterType $type = null,
        ?ValueKind  $kind = null,
        mixed       $value = null,
    )
    {
        parent::__construct($name);

        $this->input = $input;
        $this->type = $type ?? FilterType::WILDCARD;
        $this->kind = $kind ?? ValueKind::STRING;
        $this->value = $value ?? $input;
        $this->visitor = null;
    }

    public function changeValue(mixed $newValue, ?FilterType $newType = null, ?ValueKind $newKind = null): void
    {
        $this->value = $newValue;
        if (null !== $newType) {
            $this->type = $newType;
        }
        if (null !== $newKind) {
            $this->kind = $newKind;
        }
    }

    public function range(FilterRangeInterval $interval, string $value): void
    {
        if ($interval === FilterRangeInterval::NONE) {
            return;
        }

        if ($this->isRange()) {
            if ($interval === FilterRangeInterval::FROM) {
                $this->value->modify(from: $value);
            } else {
                $this->value->modify(to: $value);
            }
            return;
        }

        if ($interval === FilterRangeInterval::FROM) {
            $this->value = new FilterRange(from: $value);
        } else {
            $this->value = new FilterRange(to: $value);
        }
    }

    public function hasVisitant(): bool
    {
        return null !== $this->visitor;
    }

    public function setVisitant(FilterVisitor $visitant): void
    {
        $this->visitor = $visitant;
    }

    public function visit(mixed $builder): bool
    {
        return $this->visitor->visit($this, $builder);
    }

    public function mapping(FieldMap $fieldMap): void
    {
        parent::mapping($fieldMap);
        $this->type = $fieldMap->type();
        $this->kind = $fieldMap->kind();
        $this->parseValue();
    }

    private function parseValue(): void
    {
        if ($this->type->isList()) {
            $this->parseList();
            return;
        }

        if (FilterType::RANGE == $this->type) {
            $this->parseRange();
            return;
        }

        $this->value = $this->kind->parse($this->input);
    }

    private function parseList(): void
    {
        $listValues = ValueKind::toList($this->input);

        $method = match ($this->kind) {
            ValueKind::INTEGER => fn (string $value) => intval($value),
            ValueKind::FLOAT => fn (string $value) => floatval($value),
            ValueKind::DATE => fn (string $value) => ValueKind::toDate(trim($value)),
            default => null,
        };

        if (null !== $method) {
            $listValues = array_map($method, $listValues);
        }

        $this->value = $listValues;
    }

    private function parseRange(): void
    {
        if (!$this->isRange()) {
            $this->value = new FilterRange(from: $this->input);
        }

        $this->value->parse($this->kind);
    }

    public function hasValue(): bool
    {
        if ($this->isRange()) {
            return $this->value->hasValue();
        }

        return is_bool($this->value)
            || is_numeric($this->value)
            || !empty($this->value);
    }

    public function isRange(): bool
    {
        return $this->value instanceof FilterRange;
    }

    public function isList(): bool
    {
        return is_array($this->value);
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function input(): string
    {
        return $this->input;
    }

    public function type(): FilterType
    {
        return $this->type;
    }

    public function kind(): ValueKind
    {
        return $this->kind;
    }
}
