<?php declare(strict_types=1);

namespace App\Domain\Purchase\Exception;

use App\Domain\Identity\Exception\NotFoundException;

final class PurchaseNotFoundException extends NotFoundException
{
    protected const string DEFAULT_MESSAGE = 'Purchase not found';
}
