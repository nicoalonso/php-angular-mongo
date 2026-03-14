<?php declare(strict_types=1);

namespace App\Application\Customer\Eraser;

use App\Domain\Identity\Exception\BadRequestException;

final class CustomerAssociatedException extends BadRequestException
{
    public const string DEFAULT_MESSAGE = 'The customer is associated with one or more sales or borrows.';
}
