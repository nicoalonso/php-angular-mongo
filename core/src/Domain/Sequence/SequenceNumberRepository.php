<?php declare(strict_types=1);

namespace App\Domain\Sequence;

interface SequenceNumberRepository
{
    public function obtainByType(SequenceType $type): SequenceNumber;
    public function nextNumber(SequenceType $type): SequenceNumber;
}
