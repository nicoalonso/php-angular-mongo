<?php declare(strict_types=1);

namespace App\Domain\Sequence;

enum SequenceType: string
{
    case MEMBERSHIP = 'membership';
    case SALE = 'sale';
    case BORROW = 'borrow';

    public function getPrefix(): string
    {
        return match ($this) {
            self::MEMBERSHIP => 'SN',
            self::SALE => 'F-',
            self::BORROW => 'P-',
        };
    }
}
