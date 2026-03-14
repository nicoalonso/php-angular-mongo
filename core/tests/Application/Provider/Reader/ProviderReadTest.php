<?php

namespace App\Tests\Application\Provider\Reader;

use App\Application\Provider\Reader\ProviderRead;
use App\Domain\Provider\Exception\ProviderNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\ProviderRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class ProviderReadTest extends TestCase
{
    private ProviderRepositoryStub $repoProvider;
    private ProviderRead $reader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoProvider = new ProviderRepositoryStub();
        $this->reader = new ProviderRead($this->repoProvider);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(ProviderNotFoundException::class);

        $this->reader->dispatch('unknown-provider-id');
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoProvider->put(Ref::ProviderAmazon);

        $provider = $this->reader->dispatch('1234567890');

        self::assertEquals('Amazon', $provider->getName());
    }
}
