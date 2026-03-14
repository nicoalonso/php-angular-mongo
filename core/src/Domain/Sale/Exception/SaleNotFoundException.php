<?php declare(strict_types=1);

namespace App\Domain\Sale\Exception;

use App\Domain\Identity\Exception\NotFoundException;

final class SaleNotFoundException extends NotFoundException
{
    protected const string DEFAULT_MESSAGE = 'Sale not found';
}
