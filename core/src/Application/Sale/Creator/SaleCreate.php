<?php declare(strict_types=1);

namespace App\Application\Sale\Creator;

use App\Application\Sale\Creator\Payload\SaleInvoicePayload;
use App\Application\Sale\Creator\Payload\SaleLinePayload;
use App\Domain\Book\Book;
use App\Domain\Book\BookDescriptor;
use App\Domain\Book\BookRepository;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Bus\DomainBus;
use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Customer\Exception\CustomerNotFoundException;
use App\Domain\Sale\Exception\InvalidSaleDateException;
use App\Domain\Sale\Exception\SaleLinesEmptyException;
use App\Domain\Sale\Sale;
use App\Domain\Sale\SaleInvoice;
use App\Domain\Sale\SaleLine;
use App\Domain\Sale\SaleLineRepository;
use App\Domain\Sale\SaleRepository;
use App\Domain\Sequence\SequenceNumberRepository;
use App\Domain\Sequence\SequenceType;
use App\Domain\User\UserRepository;
use Doctrine\Common\Collections\Collection;

final class SaleCreate
{
    /**  @var array <string, Book> */
    private array $bookCache = [];
    /** @var BookDescriptor[] */
    private array $bookList = [];

    public function __construct(
        private readonly SaleRepository           $repoSale,
        private readonly SequenceNumberRepository $repoSequence,
        private readonly SaleLineRepository       $repoSaleLine,
        private readonly CustomerRepository       $repoCustomer,
        private readonly BookRepository           $repoBook,
        private readonly UserRepository           $repoUser,
        private readonly DomainBus                $bus,
    ) {}

    public function dispatch(SaleCreatePayload $payload): Sale
    {
        $this->check($payload);

        $customer = $this->findCustomer($payload->getCustomerId());
        $invoice = $this->makeInvoice($payload->getInvoice());
        $user = $this->repoUser->obtainUser();

        $number = $this->generateInvoiceNextNumber();

        $sale = new Sale(
            $customer,
            $number,
            $invoice,
            $user->getName(),
        );
        $this->repoSale->save($sale);

        $this->manageLines($sale, $payload->getLines());

        $event = new SaleCreatedEvent($sale, $this->bookList);
        $this->bus->dispatch($event);

        return $sale;
    }

    private function check(SaleCreatePayload $payload): void
    {
        if (null === $payload->getInvoice()->getDate()) {
            throw new InvalidSaleDateException('Sale date is required');
        }

        if ($payload->getLines()->isEmpty()) {
            throw new SaleLinesEmptyException();
        }

        // Checks if the books already exists, to avoid later errors and rollbacks
        foreach ($payload->getLines() as $line) {
            $this->findBook($line->getBookId());
        }
    }

    private function findCustomer(string $customerId): Customer
    {
        $customer = null;
        if (!empty($customerId)) {
            $customer = $this->repoCustomer->obtainById($customerId);
        }

        if (null === $customer) {
            throw new CustomerNotFoundException();
        }

        return $customer;
    }

    private function makeInvoice(SaleInvoicePayload $invoice): SaleInvoice
    {
        return new SaleInvoice(
            $invoice->getDate(),
            $invoice->getAmount(),
            $invoice->getTaxPercentage(),
            $invoice->getTaxes(),
            $invoice->getTotal(),
        );
    }

    private function generateInvoiceNextNumber(): string
    {
        do {
            $sequence = $this->repoSequence->nextNumber(SequenceType::SALE);
            $number = $sequence->format();

            $sale = $this->repoSale->obtainByNumber($number);
        } while (null !== $sale);

        return $number;
    }

    /**
     * @param Collection<SaleLinePayload> $lines
     */
    private function manageLines(Sale $sale, Collection $lines): void
    {
        /**  @var SaleLinePayload $line */
        foreach ($lines as $line) {
            $book = $this->findBook($line->getBookId());
            $this->addBookDescriptor($book->getDescriptor());

            $saleLine = new SaleLine(
                $sale,
                $book,
                $line->getQuantity(),
                $line->getPrice(),
                $line->getDiscount(),
                $line->getTotal(),
            );

            $this->repoSaleLine->save($saleLine);
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

    private function addBookDescriptor(BookDescriptor $descriptor): void
    {
        if (!array_key_exists($descriptor->getId(), $this->bookList)) {
            $this->bookList[$descriptor->getId()] = $descriptor;
        }
    }
}
