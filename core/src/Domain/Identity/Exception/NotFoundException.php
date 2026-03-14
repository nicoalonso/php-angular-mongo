<?php declare(strict_types=1);

namespace App\Domain\Identity\Exception;

use LogicException;

class NotFoundException extends LogicException
{
    protected const string DEFAULT_MESSAGE = 'Object not found';

    public function __construct(string $message = '')
    {
        if (empty($message)) {
            $message = static::DEFAULT_MESSAGE;
        }

        parent::__construct($message);
    }
}
