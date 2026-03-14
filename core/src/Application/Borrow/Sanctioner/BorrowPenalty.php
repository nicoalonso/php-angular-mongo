<?php declare(strict_types=1);

namespace App\Application\Borrow\Sanctioner;

use App\Domain\Borrow\Borrow;
use App\Domain\Borrow\BorrowLine;
use App\Domain\Borrow\BorrowLineRepository;
use App\Domain\Borrow\BorrowRepository;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;

final readonly class BorrowPenalty
{
    // This value should be retrieved from admin config
    private const float PENALTY_VALUE = 5;
    private const int DAYS_RANGE = 7;

    public function __construct(
        private BorrowRepository $repoBorrow,
        private BorrowLineRepository $repoBorrowLine,
        private LoggerInterface $logger,
    ) {}

    public function dispatch(): int
    {
        $this->logger->info('Start borrows over due');
        $penaltyBorrowCount = 0;

        $borrows = $this->repoBorrow->obtainByOverdue();
        $this->logger->info('Borrows found: '. $borrows->count());

        foreach ($borrows as $borrow) {
            if ($this->manageBorrow($borrow)) {
                $penaltyBorrowCount++;
            }
        }

        $this->logger->info('Total borrows handle: '. $penaltyBorrowCount);
        return $penaltyBorrowCount;
    }

    private function manageBorrow(Borrow $borrow): bool
    {
        $pendingBooks = $this->repoBorrowLine
            ->obtainByBorrow($borrow->getId())
            ->filter(fn (BorrowLine $line) => !$line->isReturned());

        $message = sprintf('For borrow %s found %d pending books', $borrow->getId(), $pendingBooks->count());
        $this->logger->info($message);

        if ($pendingBooks->isEmpty()) {
            $this->logger->error('No pending books for borrow: '. $borrow->getId());
            return false;
        }

        $amount = $this->calculatePenalty($borrow);
        $borrow->penalize($amount);
        $message = sprintf('For borrow %s penalty amount: %f', $borrow->getId(), $amount);
        $this->logger->info($message);

        /** @var BorrowLine $line */
        foreach ($pendingBooks as $line) {
            $line->penalize($amount);
            $this->repoBorrowLine->save($line);
        }
        $this->repoBorrow->save($borrow);

        return true;
    }

    private function calculatePenalty(Borrow $borrow): float
    {
        $diff = $borrow->getDueDate()->diff(new DateTimeImmutable('today midnight'));
        $weeks = floor($diff->days / self::DAYS_RANGE);

        return self::PENALTY_VALUE * $weeks;
    }
}
