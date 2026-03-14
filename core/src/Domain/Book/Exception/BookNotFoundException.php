<?php declare(strict_types=1);

namespace App\Domain\Book\Exception;

use App\Domain\Identity\Exception\NotFoundException;

final class BookNotFoundException extends NotFoundException
{
    protected const string DEFAULT_MESSAGE = 'Book not found';
}
