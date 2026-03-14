<?php declare(strict_types=1);

namespace App\Domain\Borrow\Exception;

use App\Domain\Identity\Exception\NotFoundException;

final class BorrowNotFoundException extends NotFoundException
{
    protected const string DEFAULT_MESSAGE = 'Borrow not found.';
}
