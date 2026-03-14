<?php

namespace App\Tests\Application\Editorial\Creator;

use App\Application\Editorial\Creator\EditorialCreate;
use App\Application\Editorial\Creator\EditorialCreatePayload;
use App\Domain\Editorial\Exception\EditorialAlreadyExistsException;
use App\Tests\Doubles\Infrastructure\Persistence\EditorialRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class EditorialCreateTest extends TestCase
{
    use FixturePayload;

    private EditorialRepositoryStub $repoEditorial;
    private EditorialCreate $creator;
    private EditorialCreatePayload $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoEditorial = new EditorialRepositoryStub();
        $repoUser = new UserRepositoryStub();
        $this->creator = new EditorialCreate($this->repoEditorial, $repoUser);

        $data = $this->getPayload('editorial');
        $this->payload = new EditorialCreatePayload($data);
    }

    public function testShouldFailWhenAlreadyExists(): void
    {
        $this->repoEditorial->put(Ref::EditorialAnaya);

        $this->expectException(EditorialAlreadyExistsException::class);
        $this->creator->dispatch($this->payload);
    }

    public function testShouldRunWhenCreate(): void
    {
        $editorial = $this->creator->dispatch($this->payload);

        self::assertEquals($this->payload->getName(), $editorial->getName());
        assertStored($this->repoEditorial);
    }
}
