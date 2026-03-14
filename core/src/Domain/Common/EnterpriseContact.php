<?php declare(strict_types=1);

namespace App\Domain\Common;

readonly class EnterpriseContact
{
    public function __construct(
        private string $email,
        private string $website,
        private string $phone1,
        private string $phone2,
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getWebsite(): string
    {
        return $this->website;
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
