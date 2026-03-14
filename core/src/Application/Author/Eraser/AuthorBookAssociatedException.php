<?php declare(strict_types=1);

namespace App\Application\Author\Eraser;

use App\Domain\Identity\Exception\BadRequestException;

final class AuthorBookAssociatedException extends BadRequestException
{
    public const string DEFAULT_MESSAGE = 'The author is associated with one or more books.';
}
