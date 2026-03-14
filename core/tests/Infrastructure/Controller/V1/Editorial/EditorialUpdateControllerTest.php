<?php

namespace App\Tests\Infrastructure\Controller\V1\Editorial;

use App\Application\Editorial\Updater\EditorialUpdate;
use App\Infrastructure\Controller\V1\Editorial\EditorialUpdateController;
use App\Tests\Doubles\Infrastructure\Persistence\EditorialRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class EditorialUpdateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private EditorialRepositoryStub $repoEditorial;
    private EditorialUpdate $updater;
    private EditorialUpdateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoEditorial = new EditorialRepositoryStub();
        $repoUser = new UserRepositoryStub();
        $this->updater = new EditorialUpdate(
            $this->repoEditorial,
            $repoUser,
        );
        $this->controller = new EditorialUpdateController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $payload = $this->getPayload('editorial');
        $request = $this->createRequest(request: $payload);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('not-found-id', $request, $this->updater);
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $this->repoEditorial->put(Ref::EditorialAnaya);
        $payload = $this->override(name: '')
            ->getPayload('editorial');
        $request = $this->createRequest(request: $payload);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke('not-found-id', $request, $this->updater);
    }

    public function testShouldRunWhenModify(): void
    {
        $this->repoEditorial->put(Ref::EditorialAnaya);
        $payload = $this->getPayload('editorial');
        $request = $this->createRequest(request: $payload);

        $response = $this->controller->__invoke('not-found-id', $request, $this->updater);

        self::assertResponse($response, 204);
        assertStored($this->repoEditorial);
    }
}
