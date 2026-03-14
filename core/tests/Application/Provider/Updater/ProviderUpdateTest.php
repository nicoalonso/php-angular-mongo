<?php

namespace App\Tests\Application\Provider\Updater;

use App\Application\Provider\Updater\ProviderUpdate;
use App\Application\Provider\Updater\ProviderUpdatePayload;
use App\Domain\Provider\Exception\ProviderNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\ProviderRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class ProviderUpdateTest extends TestCase
{
    use FixturePayload;

    private ProviderRepositoryStub $repoProvider;
    private ProviderUpdate $updater;
    private ProviderUpdatePayload $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoProvider = new ProviderRepositoryStub();
        $repoUser = new UserRepositoryStub();
        $this->updater = new ProviderUpdate($this->repoProvider, $repoUser);

        $data = $this->getPayload('provider');
        $this->payload = new ProviderUpdatePayload($data);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(ProviderNotFoundException::class);
        $this->updater->dispatch('invalid-id', $this->payload);
    }

    public function testShouldRunWhenUpdated(): void
    {
        $this->repoProvider->put(Ref::ProviderAmazon);

        $provider = $this->updater->dispatch('21223456', $this->payload);

        self::assertEquals($this->payload->getName(), $provider->getName());
        assertStored($this->repoProvider);
    }
}
