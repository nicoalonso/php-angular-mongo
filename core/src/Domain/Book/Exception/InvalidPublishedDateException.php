<?php declare(strict_types=1);

namespace App\Domain\Book\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class InvalidPublishedDateException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'Published date cannot be in the future.';
}
