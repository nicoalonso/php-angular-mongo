<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\Provider\Provider;
use App\Domain\Provider\ProviderRepository;
use App\Tests\Fixtures\Mothers\ProviderMother;
use App\Tests\Fixtures\Ref;

final class ProviderRepositoryStub extends EntityRepositoryStub implements ProviderRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtainByName(string $name): ?Provider
    {
        return $this->read;
    }

    protected function makeFixtures(): void
    {
        $amazon = ProviderMother::amazon();
        $this->addFixture(Ref::ProviderAmazon, $amazon);

        $bestBuy = ProviderMother::bestBuy();
        $this->addFixture(Ref::ProviderBestBuy, $bestBuy);
    }
}
