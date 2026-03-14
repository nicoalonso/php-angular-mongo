<?php declare(strict_types=1);

namespace App\Presentation\V1\Sale;

use App\Application\Sale\Reader\SaleDecorator;
use App\Domain\Sale\Sale;
use App\Domain\Sale\SaleLine;
use App\Presentation\Identity\Result;

final class SaleReadView extends Result
{
    public function __construct(SaleDecorator $sale)
    {
        parent::__construct($sale);
    }

    /**
     * @param SaleDecorator $data
     */
    public static function serialize(mixed $data): array
    {
        $lines = $data->getLines()->map(fn (SaleLine $line) => [
            'lineId' => $line->getId(),
            'book' => [
                'id' => $line->getBook()->getId(),
                'title' => $line->getBook()->getTitle(),
                'isbn' => $line->getBook()->getIsbn(),
            ],
            'quantity' => $line->getQuantity(),
            'price' => $line->getPrice(),
            'discount' => $line->getDiscount(),
            'total' => $line->getTotal(),
        ])->toArray();

        /** @var Sale $data */
        return [
            'id' => $data->getId(),
            'customer' => [
                'id' => $data->getCustomer()->getId(),
                'name' => $data->getCustomer()->getName(),
                'surname' => $data->getCustomer()->getSurname(),
                'vatNumber' => $data->getCustomer()->getVatNumber(),
                'number' => $data->getCustomer()->getNumber(),
            ],
            'number' => $data->getNumber(),
            'invoice' => [
                'date' => $data->getInvoice()->getDate()->format(DATE_ATOM),
                'amount' => $data->getInvoice()->getAmount(),
                'taxPercentage' => $data->getInvoice()->getTaxPercentage(),
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
