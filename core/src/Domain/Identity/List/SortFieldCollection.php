<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

use App\Domain\Identity\AbstractCollection;

/**
 * @template-implements AbstractCollection<SortField>
 */
final class SortFieldCollection extends AbstractCollection
{
    public function getType(): string
    {
        return SortField::class;
    }
}
