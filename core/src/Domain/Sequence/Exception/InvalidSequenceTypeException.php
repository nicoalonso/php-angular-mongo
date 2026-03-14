<?php declare(strict_types=1);

namespace App\Domain\Sequence\Exception;

use App\Domain\Identity\Exception\BadRequestException;

final class InvalidSequenceTypeException extends BadRequestException
{
    protected const string DEFAULT_MESSAGE = 'Invalid sequence type provided.';
}
