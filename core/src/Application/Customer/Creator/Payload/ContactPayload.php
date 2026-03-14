<?php declare(strict_types=1);

namespace App\Application\Customer\Creator\Payload;

use App\Domain\Identity\Payload;

final class ContactPayload extends Payload
{
    private string $email;
    private string $phone1;
    private string $phone2;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->email = $this->data->toString('email');
        $this->phone1 = $this->data->toString('phone1');
        $this->phone2 = $this->data->toString('phone2');
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone1(): string
    {
        return $this->phone1;
    }

    public function getPhone2(): string
    {
        return $this->phone2;
    }
}
