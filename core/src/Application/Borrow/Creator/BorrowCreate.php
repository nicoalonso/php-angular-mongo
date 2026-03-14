<?php declare(strict_types=1);

namespace App\Application\Borrow\Creator;

use App\Application\Borrow\Creator\Payload\BorrowLinePayload;
use App\Domain\Book\Book;
use App\Domain\Book\BookRepository;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Borrow\Borrow;
use App\Domain\Borrow\BorrowLine;
use App\Domain\Borrow\BorrowLineRepository;
use App\Domain\Borrow\BorrowRepository;
use App\Domain\Borrow\Exception\BorrowLinesEmptyException;
use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Customer\Exception\CustomerNotFoundException;
use App\Domain\Sequence\SequenceNumberRepository;
use App\Domain\Sequence\SequenceType;
use App\Domain\User\UserRepository;
use Doctrine\Common\Collections\Collection;

final class BorrowCreate
{
    /**  @var array <string, Book> */
    private array $bookCache = [];

    public function __construct(
        private readonly BorrowRepository         $repoBorrow,
        private readonly SequenceNumberRepository $repoSequence,
        private readonly BorrowLineRepository     $repoBorrowLine,
        private readonly CustomerRepository       $repoCustomer,
        private readonly BookRepository           $repoBook,
        private readonly UserRepository           $repoUser,
    ) {}

    public function dispatch(BorrowCreatePayload $payload): Borrow
    {
        $this->check($payload);

        $customer = $this->findCustomer($payload->getCustomerId());
        $user = $this->repoUser->obtainUser();

        $number = $this->generateBorrowNextNumber();

        $borrow = new Borrow(
            $customer,
            $number,
            $payload->getLines()->count(),
            $user->getName(),
        );
        $this->repoBorrow->save($borrow);

        $this->manageLines($borrow, $payload->getLines());

        return $borrow;
    }

    private function check(BorrowCreatePayload $payload): void
    {
        if ($payload->getLines()->isEmpty()) {
            throw new BorrowLinesEmptyException();
        }

        // Checks if the books already exists, to avoid later errors and rollbacks
        foreach ($payload->getLines() as $line) {
            $this->findBook($line->getBookId());
        }
    }

    private function findCustomer(string $customerId): Customer
    {
        $customer = $this->repoCustomer->obtainById($customerId);

        if (null === $customer) {
            throw new CustomerNotFoundException();
        }

        return $customer;
    }

    private function generateBorrowNextNumber(): string
    {
        do {
            $sequence = $this->repoSequence->nextNumber(SequenceType::BORROW);
            $number = $sequence->format();

            $borrow = $this->repoBorrow->obtainByNumber($number);
        } while (null !== $borrow);

        return $number;
    }

    /**
     * @param Collection<BorrowLinePayload> $lines
     */
    private function manageLines(Borrow $borrow, Collection $lines): void
    {
        /**  @var BorrowLinePayload $line */
        foreach ($lines as $line) {
            $book = $this->findBook($line->getBookId());
            $borrowLine = new BorrowLine($borrow, $book);
            $this->repoBorrowLine->save($borrowLine);
        }
    }

    private function findBook(string $bookId): Book
    {
        if (array_key_exists($bookId, $this->bookCache)) {
            return $this->bookCache[$bookId];
        }

        $book = $this->repoBook->obtainById($bookId);
        if (null === $book) {
            throw new BookNotFoundException();
        }
        $this->bookCache[$bookId] = $book;
        return $book;
    }
}
