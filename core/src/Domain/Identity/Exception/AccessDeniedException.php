<?php declare(strict_types=1);

namespace App\Domain\Identity\Exception;

use LogicException;

class AccessDeniedException extends LogicException
{
    protected const string DEFAULT_MESSAGE = 'You do not have permissions to access';

    public function __construct(string $message = '')
    {
        if (empty($message)) {
            $message = static::DEFAULT_MESSAGE;
        }

        parent::__construct($message, 403);
    }
}
