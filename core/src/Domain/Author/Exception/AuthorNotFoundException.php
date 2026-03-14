<?php declare(strict_types=1);

namespace App\Domain\Author\Exception;

use App\Domain\Identity\Exception\NotFoundException;

final class AuthorNotFoundException extends NotFoundException
{
    protected const string DEFAULT_MESSAGE = 'Author not found';
}
