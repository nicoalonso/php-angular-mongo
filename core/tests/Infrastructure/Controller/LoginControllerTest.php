<?php

namespace App\Tests\Infrastructure\Controller;

use App\Application\User\Reader\UserRead;
use App\Infrastructure\Controller\LoginController;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{
    use ControllerTestable;

    private UserRead $reader;
    private LoginController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $repoUser = new UserRepositoryStub();
        $this->reader = new UserRead($repoUser);
        $this->controller = new LoginController();
    }

    public function testShouldRunWhenLogin(): void
    {
        $response = $this->controller->__invoke($this->reader);

        $data = self::assertResponse($response);
        self::assertEquals('jdoe@gmail.com', $data['name']);
    }
}
