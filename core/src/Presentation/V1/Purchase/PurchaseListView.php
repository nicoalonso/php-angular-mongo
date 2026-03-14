<?php declare(strict_types=1);

namespace App\Presentation\V1\Purchase;

use App\Domain\Purchase\Purchase;
use App\Presentation\Identity\Result;

final class PurchaseListView extends Result
{
    public function __construct(Purchase $purchase)
    {
        parent::__construct($purchase);
    }

    /**
     * @param Purchase $data
     */
    public static function serialize(mixed $data): array
    {
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
            'createdBy' => $data->getCreatedBy(),
            'createdAt' => $data->getCreatedAt()->format(DATE_ATOM),
            'updatedBy' => $data->getUpdatedBy(),
            'updatedAt' => $data->getUpdatedAt()?->format(DATE_ATOM),
        ];
    }
}
