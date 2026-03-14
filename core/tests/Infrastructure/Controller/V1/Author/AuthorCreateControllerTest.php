<?php

namespace App\Tests\Infrastructure\Controller\V1\Author;

use App\Application\Author\Creator\AuthorCreate;
use App\Infrastructure\Controller\V1\Author\AuthorCreateController;
use App\Tests\Doubles\Infrastructure\Persistence\AuthorRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthorCreateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private AuthorRepositoryStub $repoAuthor;
    private AuthorCreate $creator;
    private array $payload;
    private AuthorCreateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoAuthor = new AuthorRepositoryStub();
        $repoUser = new UserRepositoryStub();
        $this->creator = new AuthorCreate($this->repoAuthor, $repoUser);
        $this->controller = new AuthorCreateController();

        $this->payload = $this->getPayload('author');
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);

        $request = $this->createRequest(request: $this->payload);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke($request, $this->creator);
    }

    public function testShouldRunWhenCreate(): void
    {
        $request = $this->createRequest(request: $this->payload);

        $response = $this->controller->__invoke($request, $this->creator);

        self::assertResponse($response,201);
    }
}
