<?php

namespace App\Tests\Infrastructure\Controller\V1\Editorial;

use App\Application\Editorial\Creator\EditorialCreate;
use App\Infrastructure\Controller\V1\Editorial\EditorialCreateController;
use App\Tests\Doubles\Infrastructure\Persistence\EditorialRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class EditorialCreateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private EditorialRepositoryStub $repoEditorial;
    private EditorialCreate $creator;
    private array $payload;
    private EditorialCreateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoEditorial = new EditorialRepositoryStub();
        $repoUser = new UserRepositoryStub();
        $this->creator = new EditorialCreate($this->repoEditorial, $repoUser);
        $this->controller = new EditorialCreateController();

        $this->payload = $this->getPayload('editorial');
    }

    public function testShouldFailWhenAlreadyExists(): void
    {
        $this->repoEditorial->put(Ref::EditorialAnaya);
        $request = $this->createRequest(request: $this->payload);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke($request, $this->creator);
    }

    public function testShouldRunWhenCreate(): void
    {
        $request = $this->createRequest(request: $this->payload);

        $response = $this->controller->__invoke($request, $this->creator);

        self::assertResponse($response, 201);
        assertStored($this->repoEditorial);
    }
}
