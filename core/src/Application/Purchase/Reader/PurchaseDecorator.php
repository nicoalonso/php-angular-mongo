<?php declare(strict_types=1);

namespace App\Application\Purchase\Reader;

use App\Domain\Purchase\Purchase;
use App\Domain\Purchase\PurchaseLineCollection;
use BadMethodCallException;

/**
 * @method getId()
 * @method getProvider()
 * @method getPurchasedAt()
 * @method getInvoice()
 * @method getCreatedAt()
 * @method getCreatedBy()
 * @method getUpdatedAt()
 * @method getUpdatedBy()
 */
final readonly class PurchaseDecorator
{
    public function __construct(
        private Purchase $purchase,
        private PurchaseLineCollection $lines,
    ) {}

    public function __call(string $name, array $arguments)
    {
        if (!method_exists($this->purchase, $name)) {
            throw new BadMethodCallException(sprintf('Method %s not found', $name));
        }

        return $this->purchase->{$name}(...$arguments);
    }

    public function getPurchase(): Purchase
    {
        return $this->purchase;
    }

    public function getLines(): PurchaseLineCollection
    {
        return $this->lines;
    }
}
