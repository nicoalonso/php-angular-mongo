<?php declare(strict_types=1);

namespace App\Domain\Provider\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class ProviderAlreadyExistsException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'Provider already exist';
}
