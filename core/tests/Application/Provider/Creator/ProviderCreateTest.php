<?php

namespace App\Tests\Application\Provider\Creator;

use App\Application\Provider\Creator\ProviderCreate;
use App\Application\Provider\Creator\ProviderCreatePayload;
use App\Domain\Provider\Exception\ProviderAlreadyExistsException;
use App\Tests\Doubles\Infrastructure\Persistence\ProviderRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class ProviderCreateTest extends TestCase
{
    use FixturePayload;

    private ProviderRepositoryStub $repoProvider;
    private ProviderCreate $creator;
    private ProviderCreatePayload $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoProvider = new ProviderRepositoryStub();
        $repoUser = new UserRepositoryStub();

        $this->creator = new ProviderCreate($this->repoProvider, $repoUser);

        $data = $this->getPayload('provider');
        $this->payload = new ProviderCreatePayload($data);
    }

    public function testShouldFailWhenAlreadyExists(): void
    {
        $this->repoProvider->put(Ref::ProviderAmazon);

        $this->expectException(ProviderAlreadyExistsException::class);
        $this->creator->dispatch($this->payload);
    }

    public function testShouldRunWhenCreate(): void
    {
        $provider = $this->creator->dispatch($this->payload);

        self::assertSame($this->payload->getName(), $provider->getName());
        assertStored($this->repoProvider);
    }
}
