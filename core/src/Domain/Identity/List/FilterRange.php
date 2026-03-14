<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

final class FilterRange
{
    private ValueKind $kind;
    private mixed $from;
    private mixed $to;

    public function __construct(mixed $from = '', mixed $to = '')
    {
        $this->kind = ValueKind::STRING;
        $this->from = $from;
        $this->to = $to;
    }

    public function parse(ValueKind $kind): void
    {
        $this->kind = $kind;
        if ($kind === ValueKind::DATE) {
            $this->from = trim($this->from);
            $this->to = trim($this->to);
        }

        if (!empty($this->from)) {
            $this->from = $kind->parse($this->from);
        }

        if (!empty($this->to)) {
            if ($kind !== ValueKind::DATE) {
                $this->to = $kind->parse($this->to);
            } else {
                $isToShortDate = ValueKind::isShortDate($this->to);
                $this->to = $kind->parse($this->to);

                if ($isToShortDate && $this->to->format('H:i:s') === '00:00:00') {
                    $this->to = $this->to->modify('+1 day');
                }
            }
        }
    }

    public function modify(mixed $from = '', mixed $to = ''): void
    {
        if (!empty($from)) {
            $this->from = $from;
        }
        if (!empty($to)) {
            $this->to = $to;
        }
    }

    public function hasValue(): bool
    {
        return $this->hasFrom() || $this->hasTo();
    }

    public function hasFrom(): bool
    {
        return !is_string($this->from) || !empty($this->from);
    }

    public function hasTo(): bool
    {
        return !is_string($this->to) || !empty($this->to);
    }

    public function kind(): ValueKind
    {
        return $this->kind;
    }

    public function from(): mixed
    {
        return $this->from;
    }

    public function to(): mixed
    {
        return $this->to;
    }
}