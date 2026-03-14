<?php declare(strict_types=1);

namespace App\Application\Purchase\Creator;

use App\Application\Purchase\Creator\Payload\PurchaseInvoicePayload;
use App\Application\Purchase\Creator\Payload\PurchaseLinePayload;
use App\Domain\Book\Book;
use App\Domain\Book\BookDescriptor;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Provider\Exception\ProviderNotFoundException;
use App\Domain\Provider\Provider;
use App\Domain\Purchase\Exception\InvalidPurchaseDateException;
use App\Domain\Purchase\Purchase;
use App\Domain\Purchase\PurchaseInvoice;
use App\Domain\Purchase\PurchaseLine;
use App\Domain\Purchase\PurchaseLineCollection;
use Doctrine\Common\Collections\Collection;

trait PurchaseMakeable
{
    /**  @var array <string, Book> */
    private array $bookCache = [];
    /** @var BookDescriptor[] */
    private array $bookList = [];

    private function check(PurchaseCreatePayload $payload): void
    {
        if (null === $payload->getPurchasedAt()) {
            throw new InvalidPurchaseDateException('Purchased date is required');
        }

        // Checks if the books already exists, to avoid later errors and rollbacks
        foreach ($payload->getLines() as $line) {
            $this->findBook($line->getBookId());
        }
    }

    private function findProvider(string $providerId): Provider
    {
        $provider = null;
        if (!empty($providerId)) {
            $provider = $this->repoProvider->obtainById($providerId);
        }

        if (null === $provider) {
            throw new ProviderNotFoundException();
        }

        return $provider;
    }

    private function makeInvoice(PurchaseInvoicePayload $invoice): PurchaseInvoice
    {
        return new PurchaseInvoice(
            $invoice->getNumber(),
            $invoice->getAmount(),
            $invoice->getTaxes(),
            $invoice->getTotal(),
        );
    }

    /**
     * @param Collection<PurchaseLinePayload> $lines
     * @param ?Collection<PurchaseLine> $current
     */
    private function manageLines(Purchase $purchase, Collection $lines, ?Collection $current = null): void
    {
        if (null === $current) {
            $current = new PurchaseLineCollection();
        }

        /**  @var PurchaseLinePayload $line */
        foreach ($lines as $line) {
            $book = $this->findBook($line->getBookId());
            $this->addBookDescriptor($book->getDescriptor());

            $purchaseLine = $current->findFirst(fn ($key, PurchaseLine $l) => $l->getId() === $line->getLineId());
            if (null === $purchaseLine) {
                $purchaseLine = new PurchaseLine(
                    $purchase,
                    $book,
                    $line->getQuantity(),
                    $line->getUnitPrice(),
                    $line->getDiscountPercentage(),
                    $line->getTotal(),
                );
            } else {
                $current->removeElement($purchaseLine);

                $purchaseLine->modify(
                    $book,
                    $line->getQuantity(),
                    $line->getUnitPrice(),
                    $line->getDiscountPercentage(),
                    $line->getTotal(),
                );
            }

            $this->repoPurchaseLine->save($purchaseLine);
        }

        foreach ($current as $line) {
            $this->addBookDescriptor($line->getBook());
            $this->repoPurchaseLine->remove($line);
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

    /**
     * @return array<BookDescriptor>
     */
    private function getBookList(): array
    {
        return array_values($this->bookList);
    }
}
