<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

enum FilterType
{
    case WILDCARD;
    case MATCH;
    case FUZZY;
    case RANGE;
    case IN;
    case ALL;
    case EXISTS;

    public function isList(): bool
    {
        return match ($this) {
            self::IN, self::ALL => true,
            default => false,
        };
    }
}
