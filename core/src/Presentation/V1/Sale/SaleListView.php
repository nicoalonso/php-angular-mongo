<?php declare(strict_types=1);

namespace App\Presentation\V1\Sale;

use App\Domain\Sale\Sale;
use App\Presentation\Identity\Result;

final class SaleListView extends Result
{
    public function __construct(Sale $sale)
    {
        parent::__construct($sale);
    }

    /**
     * @param Sale $data
     */
    public static function serialize(mixed $data): array
    {
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
            'createdBy' => $data->getCreatedBy(),
            'createdAt' => $data->getCreatedAt()->format(DATE_ATOM),
            'updatedBy' => $data->getUpdatedBy(),
            'updatedAt' => $data->getUpdatedAt()?->format(DATE_ATOM),
        ];
    }
}
