<?php declare(strict_types=1);

namespace App\Domain\Identity;

final class StringCollection extends AbstractCollection
{
    public function getType(): string
    {
        return 'string';
    }
}
