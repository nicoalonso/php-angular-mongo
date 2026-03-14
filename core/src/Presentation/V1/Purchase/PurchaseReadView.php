<?php declare(strict_types=1);

namespace App\Presentation\V1\Purchase;

use App\Application\Purchase\Reader\PurchaseDecorator;
use App\Domain\Purchase\Purchase;
use App\Domain\Purchase\PurchaseLine;
use App\Presentation\Identity\Result;

final class PurchaseReadView extends Result
{
    public function __construct(PurchaseDecorator $purchase)
    {
        parent::__construct($purchase);
    }

    /**
     * @param PurchaseDecorator $data
     */
    public static function serialize(mixed $data): array
    {
        $lines = $data->getLines()->map(fn (PurchaseLine $line) => [
            'lineId' => $line->getId(),
            'book' => [
                'id' => $line->getBook()->getId(),
                'title' => $line->getBook()->getTitle(),
                'isbn' => $line->getBook()->getIsbn(),
            ],
            'quantity' => $line->getQuantity(),
            'unitPrice' => $line->getUnitPrice(),
            'discountPercentage' => $line->getDiscountPercentage(),
            'total' => $line->getTotal(),
        ])->toArray();

        /** @var Purchase $data */
        return [
            'id' => $data->getId(),
            'provider' => [
                'id' => $data->getProvider()->getId(),
                'name' => $data->getProvider()->getName(),
            ],
            'purchasedAt' => $data->getPurchasedAt()->format(DATE_ATOM),
            'invoice' => [
                'number' => $data->getInvoice()->getNumber(),
                'amount' => $data->getInvoice()->getAmount(),
                'taxes' => $data->getInvoice()->getTaxes(),
                'total' => $data->getInvoice()->getTotal(),
            ],
            'lines' => $lines,
            'createdBy' => $data->getCreatedBy(),
            'createdAt' => $data->getCreatedAt()->format(DATE_ATOM),
            'updatedBy' => $data->getUpdatedBy(),
            'updatedAt' => $data->getUpdatedAt()?->format(DATE_ATOM),
        ];
    }
}
