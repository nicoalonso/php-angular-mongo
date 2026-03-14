<?php declare(strict_types=1);

namespace App\Application\Book\Inventory;

use App\Domain\Book\Book;
use App\Domain\Book\BookDescriptor;
use App\Domain\Book\BookRepository;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Purchase\PurchaseLineRepository;
use App\Domain\Sale\SaleLineRepository;
use Psr\Log\LoggerInterface;

final readonly class BookInventory
{
    public function __construct(
        private BookRepository $repoBook,
        private PurchaseLineRepository $repoPurchaseLine,
        private SaleLineRepository $repoSaleLine,
        private LoggerInterface $logger,
    ) {}

    public function dispatch(BookDescriptor $descriptor): void
    {
        $this->logger->info("Make inventory for book ". $descriptor->getTitle(), [
            'bookId' => $descriptor->getId(),
        ]);
        $book = $this->getBookOrFail($descriptor);

        $stock = 0;
        $purchaseLines = $this->repoPurchaseLine->obtainByBook($book->getId());
        $this->logger->info('Found '.count($purchaseLines).' purchase lines ');

        foreach ($purchaseLines as $line) {
            $stock += $line->getQuantity();
        }

        $saleLines = $this->repoSaleLine->obtainByBook($book->getId());
        $this->logger->info('Found '.count($saleLines).' sale lines ');

        foreach ($saleLines as $line) {
            $stock -= $line->getQuantity();
        }

        $this->logger->info('Calculated stock: '. $stock);
        if ($stock < 0) {
            $this->logger->error('Calculated stock is negative, setting to 0');
            $stock = 0;
        }

        $book->changeStock($stock);
        $this->repoBook->save($book);
    }

    public function getBookOrFail(BookDescriptor $descriptor): Book
    {
        $book = $this->repoBook->obtainById($descriptor->getId());
        if (null === $book) {
            throw new BookNotFoundException();
        }
        return $book;
    }
}
