<?php declare(strict_types=1);

namespace App\Domain\Borrow;

use App\Domain\Borrow\Exception\InvalidBorrowNumberException;
use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerDescriptor;
use App\Domain\Identity\Entity;
use DateTimeImmutable;

class Borrow extends Entity
{
    private const string INTERVAL_DUE_DATE = '+14 day';

    private CustomerDescriptor $customer;
    private string $number;
    private DateTimeImmutable $borrowDate;
    private int $totalBooks;
    private DateTimeImmutable $dueDate;
    private int $totalReturnedBooks;
    private bool $returned;
    private ?DateTimeImmutable $returnedDate;
    private bool $penalty;
    private float $penaltyAmount;

    public function __construct(
        Customer $customer,
        string $number,
        int $totalBooks,
        string $createdBy,
    )
    {
        parent::__construct($createdBy);
        $this->check($number);

        $this->customer = $customer->getDescriptor();
        $this->number = $number;
        $this->totalBooks = $totalBooks;

        $this->borrowDate = new DateTimeImmutable();
        $this->dueDate = new DateTimeImmutable(self::INTERVAL_DUE_DATE);
        $this->totalReturnedBooks = 0;
        $this->returned = false;
        $this->returnedDate = null;
        $this->penalty = false;
        $this->penaltyAmount = 0.0;
    }

    private function check(string $number): void
    {
        if (empty($number)) {
            throw new InvalidBorrowNumberException();
        }
    }

    public function modify(int $returnedBooks, string $updatedBy): void
    {
        $this->totalReturnedBooks = $returnedBooks;
        $this->returned = ($this->totalReturnedBooks >= $this->totalBooks);
        if ($this->returned) {
            $this->returnedDate = new DateTimeImmutable();
        }

        $this->updated($updatedBy);
    }

    public function penalize(float $amount): void
    {
        $this->penalty = true;
        $this->penaltyAmount = $amount;
    }

    public function getCustomer(): CustomerDescriptor
    {
        return $this->customer;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getBorrowDate(): DateTimeImmutable
    {
        return $this->borrowDate;
    }

    public function getTotalBooks(): int
    {
        return $this->totalBooks;
    }

    public function getDueDate(): DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function getTotalReturnedBooks(): int
    {
        return $this->totalReturnedBooks;
    }

    public function isReturned(): bool
    {
        return $this->returned;
    }

    public function getReturnedDate(): ?DateTimeImmutable
    {
        return $this->returnedDate;
    }

    public function hasPenalty(): bool
    {
        return $this->penalty;
    }

    public function getPenaltyAmount(): float
    {
        return $this->penaltyAmount;
    }
}
