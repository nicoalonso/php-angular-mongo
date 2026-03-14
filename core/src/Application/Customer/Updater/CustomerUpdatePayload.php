<?php declare(strict_types=1);

namespace App\Application\Customer\Updater;

use App\Application\Customer\Creator\CustomerCreatePayload;

class CustomerUpdatePayload extends CustomerCreatePayload
{
    private bool $active;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $membershipData = $this->data->toValet('membership');
        $this->active = $membershipData->toBool('active', true);
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
