<?php declare(strict_types=1);

namespace App\Domain\Borrow;

use App\Domain\Identity\AbstractCollection;

/**
 * @extends AbstractCollection<BorrowLine>
 */
final class BorrowLineCollection extends AbstractCollection
{
    public function getType(): string
    {
        return BorrowLine::class;
    }
}
