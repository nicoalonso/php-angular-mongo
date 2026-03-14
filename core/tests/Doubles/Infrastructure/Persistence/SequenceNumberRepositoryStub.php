<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\Sequence\SequenceNumber;
use App\Domain\Sequence\SequenceNumberRepository;
use App\Domain\Sequence\SequenceType;

final class SequenceNumberRepositoryStub implements SequenceNumberRepository
{
    public function obtainByType(SequenceType $type): SequenceNumber
    {
        return new SequenceNumber($type);
    }

    public function nextNumber(SequenceType $type): SequenceNumber
    {
        $number = new SequenceNumber($type);
        $number->next();
        return $number;
    }
}
