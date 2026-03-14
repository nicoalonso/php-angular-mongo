<?php declare(strict_types=1);

namespace App\Application\Sequence\Simulator;

use App\Domain\Sequence\Exception\InvalidSequenceTypeException;
use App\Domain\Sequence\SequenceNumber;
use App\Domain\Sequence\SequenceNumberRepository;
use App\Domain\Sequence\SequenceType;

final readonly class SequenceSimulate
{
    public function __construct(
        private SequenceNumberRepository $repoSequenceNumber,
    ) {}

    public function dispatch(string $type): SequenceNumber
    {
        $sequenceType = SequenceType::tryFrom($type);
        if (null === $sequenceType) {
            throw new InvalidSequenceTypeException();
        }

        return $this->repoSequenceNumber->obtainByType($sequenceType);
    }
}
