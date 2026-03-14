<?php declare(strict_types=1);

namespace App\Domain\Purchase;

use App\Domain\Identity\AbstractCollection;

/**
 * @template-extends AbstractCollection<PurchaseLine>
 */
final class PurchaseLineCollection extends AbstractCollection
{
    public function getType(): string
    {
        return PurchaseLine::class;
    }
}
