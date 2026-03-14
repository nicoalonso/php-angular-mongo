<?php declare(strict_types=1);

namespace App\Presentation\Identity\Exception;

use LogicException;

final class EntityViewClassNotExistException extends LogicException
{
    public function __construct(string $className)
    {
        $message = sprintf("The class '%s' not exist", $className);
        parent::__construct($message);
    }
}
