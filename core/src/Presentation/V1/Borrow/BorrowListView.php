<?php declare(strict_types=1);

namespace App\Presentation\V1\Borrow;

use App\Domain\Borrow\Borrow;
use App\Presentation\Identity\Result;

final class BorrowListView extends Result
{
    public function __construct(Borrow $borrow)
    {
        parent::__construct($borrow);
    }

    /**
     * @param Borrow $data
     */
    public static function serialize(mixed $data): array
    {
        /** @var Borrow $data */
        return [
            'id' => $data->getId(),
            'customer' => [
                'id' => $data->getCustomer()->getId(),
                'name' => $data->getCustomer()->getName(),
                'surname' => $data->getCustomer()->getSurname(),
                'vatNumber' => $data->getCustomer()->getVatNumber(),
                'number' => $data->getCustomer()->getNumber(),
            ],
            'number' => $data->getNumber(),
            'borrowDate' => $data->getBorrowDate()->format(DATE_ATOM),
            'totalBooks' => $data->getTotalBooks(),
            'dueDate' => $data->getDueDate()->format(DATE_ATOM),
            'totalReturnedBooks' => $data->getTotalReturnedBooks(),
            'returned' => $data->isReturned(),
            'returnedDate' => $data->getReturnedDate()?->format(DATE_ATOM),
            'penalty' => $data->hasPenalty(),
            'penaltyAmount' => $data->getPenaltyAmount(),
            'createdBy' => $data->getCreatedBy(),
            'createdAt' => $data->getCreatedAt()->format(DATE_ATOM),
            'updatedBy' => $data->getUpdatedBy(),
            'updatedAt' => $data->getUpdatedAt()?->format(DATE_ATOM),
        ];
    }
}
