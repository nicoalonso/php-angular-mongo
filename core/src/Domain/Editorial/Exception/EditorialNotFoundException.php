<?php declare(strict_types=1);

namespace App\Domain\Editorial\Exception;

use App\Domain\Identity\Exception\NotFoundException;

final class EditorialNotFoundException extends NotFoundException
{
    protected const string DEFAULT_MESSAGE = 'Editorial not found';
}
