<?php declare(strict_types=1);

namespace App\Domain\Customer\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class CustomerAlreadyExistException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'Customer already exist';
}
