<?php declare(strict_types=1);

namespace App\Domain\Sale;

use App\Domain\Identity\AbstractCollection;

/**
 * @template-extends AbstractCollection<SaleLine>
 */
final class SaleLineCollection extends AbstractCollection
{
    public function getType(): string
    {
        return SaleLine::class;
    }
}
