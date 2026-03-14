<?php declare(strict_types=1);

namespace App\Domain\Author\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class InvalidBirthDateException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'Birth date cannot be in the future.';
}