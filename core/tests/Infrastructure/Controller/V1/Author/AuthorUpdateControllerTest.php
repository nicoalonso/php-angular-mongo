<?php

namespace App\Tests\Infrastructure\Controller\V1\Author;

use App\Application\Author\Updater\AuthorUpdate;
use App\Infrastructure\Controller\V1\Author\AuthorUpdateController;
use App\Tests\Doubles\Infrastructure\Persistence\AuthorRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorUpdateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private AuthorRepositoryStub $repoAuthor;
    private AuthorUpdate $updater;
    private AuthorUpdateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoAuthor = new AuthorRepositoryStub();
        $repoUser = new UserRepositoryStub();
        $this->updater = new AuthorUpdate($this->repoAuthor, $repoUser);
        $this->controller = new AuthorUpdateController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $payload = $this->getPayload('author');
        $request = $this->createRequest(request: $payload);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('non-existing-id', $request, $this->updater);
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);

        $payload = $this->override(name: '')->getPayload('author');
        $request = $this->createRequest(request: $payload);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke('non-existing-id', $request, $this->updater);
    }

    public function testShouldRunWhenModify(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);

        $payload = $this->getPayload('author');
        $request = $this->createRequest(request: $payload);

        $response = $this->controller->__invoke('non-existing-id', $request, $this->updater);

        self::assertResponse($response, 204);
    }
}
