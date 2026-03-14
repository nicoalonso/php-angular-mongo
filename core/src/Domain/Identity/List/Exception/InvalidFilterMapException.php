<?php declare(strict_types=1);

namespace App\Domain\Identity\List\Exception;

use LogicException;

final class InvalidFilterMapException extends LogicException
{
    private const string DEFAULT_MESSAGE_ERROR = 'The filter map is invalid';

    public function __construct($message = "")
    {
        if (empty($message)) {
            $message = self::DEFAULT_MESSAGE_ERROR;
        }
        parent::__construct($message);
    }
}
