<?php declare(strict_types=1);

namespace App\Application\Borrow\CheckIn;

use App\Application\Borrow\Creator\Payload\BorrowLinePayload;
use App\Domain\Borrow\Borrow;
use App\Domain\Borrow\BorrowLine;
use App\Domain\Borrow\BorrowLineCollection;
use App\Domain\Borrow\BorrowLineRepository;
use App\Domain\Borrow\BorrowRepository;
use App\Domain\Borrow\Exception\BorrowNotFoundException;
use App\Domain\User\UserRepository;
use Doctrine\Common\Collections\Collection;

final readonly class BorrowCheckIn
{
    public function __construct(
        private BorrowRepository     $repoBorrow,
        private BorrowLineRepository $repoBorrowLine,
        private UserRepository       $repoUser,
    ) {}

    public function dispatch(string $borrowId, BorrowCheckInPayload $payload): Borrow
    {
        $borrow = $this->getBorrowOrFail($borrowId);
        $lines = $this->repoBorrowLine->obtainByBorrow($borrowId);

        $this->checkinLines($lines, $payload->getLines());

        $countReturned = $lines->filter(fn (BorrowLine $line) => $line->isReturned())->count();

        $user = $this->repoUser->obtainUser();
        $borrow->modify($countReturned, $user->getName());
        $this->repoBorrow->save($borrow);

        return $borrow;
    }

    private function getBorrowOrFail(string $borrowId): Borrow
    {
        $borrow = $this->repoBorrow->obtainById($borrowId);
        if (!$borrow) {
            throw new BorrowNotFoundException();
        }

        return $borrow;
    }

    /**
     * @param Collection<BorrowLinePayload> $payloadLines
     */
    private function checkinLines(BorrowLineCollection $lines, Collection $payloadLines): void
    {
        foreach ($payloadLines as $payloadLine) {
            if (!$payloadLine->isReturned()) {
                continue;
            }

            $line = $lines->findFirst(fn ($key, BorrowLine $item) => $item->getId() == $payloadLine->getLineId());
            if (!$line) {
                continue;
            }

            $line->checkIn();
            $this->repoBorrowLine->save($line);
        }
    }
}
