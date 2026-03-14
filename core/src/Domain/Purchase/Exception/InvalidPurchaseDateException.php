<?php declare(strict_types=1);

namespace App\Domain\Purchase\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class InvalidPurchaseDateException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'Purchase date cannot be in the future.';
}
