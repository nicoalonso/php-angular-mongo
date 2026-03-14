<?php declare(strict_types=1);

namespace App\Domain\Identity\Exception;

final class NameEmptyException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'Name is required';
}
