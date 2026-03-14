<?php declare(strict_types=1);

namespace App\Domain\Sale\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class InvalidSaleInvoiceNumberException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'The sale invoice number is required.';
}