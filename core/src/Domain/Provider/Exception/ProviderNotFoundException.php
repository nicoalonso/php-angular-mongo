<?php declare(strict_types=1);

namespace App\Domain\Provider\Exception;

use App\Domain\Identity\Exception\NotFoundException;

final class ProviderNotFoundException extends NotFoundException
{
    protected const string DEFAULT_MESSAGE = 'Provider not found';
}