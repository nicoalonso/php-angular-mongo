<?php declare(strict_types=1);

namespace App\Application\Book\Eraser;

use App\Domain\Identity\Exception\BadRequestException;

final class BookAssociatedException extends BadRequestException
{
    public const string DEFAULT_MESSAGE = 'The book is associated with one or more purchases or sales.';
}
