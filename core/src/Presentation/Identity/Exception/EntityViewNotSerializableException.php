<?php declare(strict_types=1);

namespace App\Presentation\Identity\Exception;

use LogicException;

final class EntityViewNotSerializableException extends LogicException
{
    public function __construct(string $className)
    {
        $message = sprintf("The class '%s' is not serializable", $className);
        parent::__construct($message);
    }
}
