<?php declare(strict_types=1);

namespace App\Domain\Book\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class InvalidIsbnException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'Invalid ISBN format. Expected format: 978-1234567890';
}
