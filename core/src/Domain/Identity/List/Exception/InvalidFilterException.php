<?php declare(strict_types=1);

namespace App\Domain\Identity\List\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class InvalidFilterException extends BadRequestException
{
    private const string MESSAGE_FORMAT = "Invalid filter: %s";

    public function __construct(string $name)
    {
        $message = sprintf(self::MESSAGE_FORMAT, $name);
        parent::__construct($message);
    }
}
