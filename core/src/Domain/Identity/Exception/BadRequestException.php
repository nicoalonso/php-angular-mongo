<?php declare(strict_types=1);

namespace App\Domain\Identity\Exception;

use InvalidArgumentException;

class BadRequestException extends InvalidArgumentException
{
    protected const string DEFAULT_MESSAGE = 'Invalid argument';

    public function __construct(string $message = '')
    {
        if (empty($message)) {
            $message = static::DEFAULT_MESSAGE;
        }

        parent::__construct($message, 400);
    }
}
