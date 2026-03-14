<?php declare(strict_types=1);

namespace App\Domain\Purchase;

use App\Domain\Identity\AbstractCollection;

/**
 * @template-extends AbstractCollection<Purchase>
 */
final class PurchaseCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Purchase::class;
    }
}
