<?php declare(strict_types=1);

namespace App\Domain\Sale\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class InvalidSaleDateException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'Sale date cannot be in the future.';
}
