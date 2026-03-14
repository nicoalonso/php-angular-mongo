<?php declare(strict_types=1);

namespace App\Presentation\V1\Borrow;

use App\Application\Borrow\Reader\BorrowDecorator;
use App\Domain\Borrow\Borrow;
use App\Domain\Borrow\BorrowLine;
use App\Presentation\Identity\Result;

final class BorrowReadView extends Result
{
    public function __construct(BorrowDecorator $borrow)
    {
        parent::__construct($borrow);
    }

    /**
     * @param BorrowDecorator $data
     */
    public static function serialize(mixed $data): array
    {
        $lines = $data->getLines()->map(fn (BorrowLine $line) => [
            'lineId' => $line->getId(),
            'book' => [
                'id' => $line->getBook()->getId(),
                'title' => $line->getBook()->getTitle(),
                'isbn' => $line->getBook()->getIsbn(),
            ],
            'returned' => $line->isReturned(),
            'returnedDate' => $line->getReturnedDate()?->format(DATE_ATOM),
            'penalty' => $line->hasPenalty(),
            'penaltyAmount' => $line->getPenaltyAmount(),
        ])->toArray();

        /** @var Borrow $data */
        return [
            'id' => $data->getId(),
            'number' => $data->getNumber(),
            'customer' => [
                'id' => $data->getCustomer()->getId(),
                'name' => $data->getCustomer()->getName(),
                'surname' => $data->getCustomer()->getSurname(),
                'vatNumber' => $data->getCustomer()->getVatNumber(),
                'number' => $data->getCustomer()->getNumber(),
            ],
            'borrowDate' => $data->getBorrowDate()->format(DATE_ATOM),
            'totalBooks' => $data->getTotalBooks(),
            'dueDate' => $data->getDueDate()->format(DATE_ATOM),
            'totalReturnedBooks' => $data->getTotalReturnedBooks(),
            'returned' => $data->isReturned(),
            'returnedDate' => $data->getReturnedDate()?->format(DATE_ATOM),
            'penalty' => $data->hasPenalty(),
            'penaltyAmount' => $data->getPenaltyAmount(),
            'lines' => $lines,
            'createdBy' => $data->getCreatedBy(),
            'createdAt' => $data->getCreatedAt()->format(DATE_ATOM),
            'updatedBy' => $data->getUpdatedBy(),
            'updatedAt' => $data->getUpdatedAt()?->format(DATE_ATOM),
        ];
    }
}
