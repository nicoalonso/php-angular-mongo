<?php declare(strict_types=1);

namespace App\Application\Sale\Reader;

use App\Domain\Sale\Sale;
use App\Domain\Sale\SaleLineCollection;
use BadMethodCallException;

/**
 * @method getId()
 * @method getCustomer()
 * @method getNumber()
 * @method getInvoice()
 * @method getCreatedBy()
 * @method getCreatedAt()
 * @method getUpdatedBy()
 * @method getUpdatedAt()
 */
final readonly class SaleDecorator
{
    public function __construct(
        private Sale $sale,
        private SaleLineCollection $lines,
    ) {}

    public function __call(string $name, array $arguments)
    {
        if (!method_exists($this->sale, $name)) {
            throw new BadMethodCallException(sprintf('Method %s not found', $name));
        }

        return $this->sale->{$name}(...$arguments);
    }

    public function getSale(): Sale
    {
        return $this->sale;
    }

    public function getLines(): SaleLineCollection
    {
        return $this->lines;
    }
}
