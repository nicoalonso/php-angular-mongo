<?php declare(strict_types=1);

namespace App\Domain\Borrow;

use App\Domain\Identity\AbstractCollection;

/**
 * @extends AbstractCollection<Borrow>
 */
final class BorrowCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Borrow::class;
    }
}
