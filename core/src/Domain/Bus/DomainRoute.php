<?php declare(strict_types=1);

namespace App\Domain\Bus;

enum DomainRoute: string
{
    case NONE = '';
    case ALL = 'all.messages';
    case LIBRARY = 'app.library';

    public function has(): bool
    {
        return $this !== self::NONE;
    }
}
