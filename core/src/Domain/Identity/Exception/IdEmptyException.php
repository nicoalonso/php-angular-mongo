<?php declare(strict_types=1);

namespace App\Domain\Identity\Exception;

final class IdEmptyException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'Id is required';
}
