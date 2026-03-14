<?php declare(strict_types=1);

namespace App\Domain\Purchase\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class InvalidPurchaseInvoiceNumberException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'The purchase invoice number is required.';
}
