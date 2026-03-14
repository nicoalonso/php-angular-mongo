<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

use App\Domain\Identity\AbstractCollection;

/**
 * @template-implements AbstractCollection<FilterField>
 */
final class FilterFieldCollection extends AbstractCollection
{
    public function getType(): string
    {
        return FilterField::class;
    }
}
