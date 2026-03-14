<?php declare(strict_types=1);

namespace App\Domain\Borrow\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class BorrowLinesEmptyException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'The borrow must have at least one line.';
}
