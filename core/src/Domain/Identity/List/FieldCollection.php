<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

use App\Domain\Identity\AbstractCollection;

/**
 * @template-implements AbstractCollection<Field>
 */
final class FieldCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Field::class;
    }
}
