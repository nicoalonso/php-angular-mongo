<?php declare(strict_types=1);

namespace App\Domain\Author\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class InvalidDeathDateException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'Death date cannot be in the future.';
}