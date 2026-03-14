<?php declare(strict_types=1);

namespace App\Domain\Customer\Exception;

use App\Domain\Identity\Exception\NotFoundException;

final class CustomerNotFoundException extends NotFoundException
{
    protected const string DEFAULT_MESSAGE = 'Customer not found';
}
