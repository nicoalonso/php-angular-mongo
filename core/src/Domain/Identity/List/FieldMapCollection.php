<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

use App\Domain\Identity\AbstractCollection;

/**
 * @template-implements AbstractCollection<FieldMap>
 */
final class FieldMapCollection extends AbstractCollection
{
    public function getType(): string
    {
        return FieldMap::class;
    }
}
