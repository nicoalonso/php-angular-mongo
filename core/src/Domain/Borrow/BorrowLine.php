<?php declare(strict_types=1);

namespace App\Domain\Borrow;

use App\Domain\Book\Book;
use App\Domain\Book\BookDescriptor;
use App\Domain\Identity\Identity;
use DateTimeImmutable;

class BorrowLine extends Identity
{
    private Borrow $borrow;
    private BookDescriptor $book;
    private bool $returned;
    private ?DateTimeImmutable $returnedDate;
    private bool $penalty;
    private float $penaltyAmount;

    public function __construct(Borrow $borrow, Book $book)
    {
        parent::__construct();

        $this->borrow = $borrow;
        $this->book = $book->getDescriptor();
        $this->returned = false;
        $this->returnedDate = null;
        $this->penalty = false;
        $this->penaltyAmount = 0.0;
    }

    public function checkIn(): void
    {
        $this->returned = true;
        $this->returnedDate = new DateTimeImmutable();
    }

    public function penalize(float $amount): void
    {
        $this->penalty = true;
        $this->penaltyAmount = $amount;
    }

    public function getBorrow(): Borrow
    {
        return $this->borrow;
    }

    public function getBook(): BookDescriptor
    {
        return $this->book;
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
