<?php declare(strict_types=1);

namespace App\Domain\Editorial\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class EditorialAlreadyExistsException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'Editorial already exist';
}
