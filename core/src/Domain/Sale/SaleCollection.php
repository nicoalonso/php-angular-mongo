<?php declare(strict_types=1);

namespace App\Domain\Sale;

use App\Domain\Identity\AbstractCollection;

/**
 * @template-extends AbstractCollection<Sale>
 */
final class SaleCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Sale::class;
    }
}
