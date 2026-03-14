<?php declare(strict_types=1);

namespace App\Application\Borrow\Reader;

use App\Domain\Borrow\Borrow;
use App\Domain\Borrow\BorrowLineCollection;
use BadMethodCallException;

/**
 * @method getId()
 * @method getCustomer()
 * @method getNumber()
 * @method getBorrowDate()
 * @method getTotalBooks()
 * @method getDueDate()
 * @method getTotalReturnedBooks()
 * @method isReturned()
 * @method isPenalty()
 * @method getCreatedBy()
 * @method getCreatedAt()
 * @method getUpdatedBy()
 * @method getUpdatedAt()
 */
final readonly class BorrowDecorator
{
    public function __construct(
        private Borrow $borrow,
        private BorrowLineCollection $lines,
    ) {}

    public function __call(string $name, array $arguments)
    {
        if (!method_exists($this->borrow, $name)) {
            throw new BadMethodCallException(sprintf('Method %s not found', $name));
        }

        return $this->borrow->{$name}(...$arguments);
    }

    public function getBorrow(): Borrow
    {
        return $this->borrow;
    }

    public function getLines(): BorrowLineCollection
    {
        return $this->lines;
    }
}
