<?php declare(strict_types=1);

namespace App\Application\Editorial\Eraser;

use App\Domain\Identity\Exception\BadRequestException;

final class EditorialBookAssociatedException extends BadRequestException
{
    public const string DEFAULT_MESSAGE = 'The editorial is associated with one or more books.';
}
