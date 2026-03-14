<?php declare(strict_types=1);

namespace App\Application\Provider\Eraser;

use App\Domain\Identity\Exception\BadRequestException;

final class ProviderAssociatedException extends BadRequestException
{
    public const string DEFAULT_MESSAGE = 'Provider has purchases and cannot be deleted';
}
