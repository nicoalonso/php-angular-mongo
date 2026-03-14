<?php

namespace App\Tests\Application\Editorial\Updater;

use App\Application\Editorial\Updater\EditorialUpdate;
use App\Application\Editorial\Updater\EditorialUpdatePayload;
use App\Domain\Editorial\Exception\EditorialNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\EditorialRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class EditorialUpdateTest extends TestCase
{
    use FixturePayload;

    private EditorialRepositoryStub $repoEditorial;
    private EditorialUpdate $updater;
    private EditorialUpdatePayload $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoEditorial = new EditorialRepositoryStub();
        $repoUser = new UserRepositoryStub();
        $this->updater = new EditorialUpdate(
            $this->repoEditorial,
            $repoUser,
        );

        $data = $this->getPayload('editorial');
        $this->payload = new EditorialUpdatePayload($data);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(EditorialNotFoundException::class);
        $this->updater->dispatch('not-found-id', $this->payload);
    }

    public function testShouldRunWhenModify(): void
    {
        $this->repoEditorial->put(Ref::EditorialAnaya);

        $editorial = $this->updater->dispatch('1234567', $this->payload);

        self::assertEquals($this->payload->getName(), $editorial->getName());
        assertStored($this->repoEditorial);
    }
}
