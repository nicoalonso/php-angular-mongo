<?php declare(strict_types=1);

namespace App\Domain\Book\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class TitleEmptyException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'Title cannot be empty';
}
