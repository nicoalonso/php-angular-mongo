<?php

namespace App\Tests\Application\User\Reader;

use App\Application\User\Reader\UserRead;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use PHPUnit\Framework\TestCase;

class UserReadTest extends TestCase
{
    private UserRead $reader;

    protected function setUp(): void
    {
        parent::setUp();

        $repoUser = new UserRepositoryStub();
        $this->reader = new UserRead($repoUser);
    }

    public function testShouldRunWhenRead(): void
    {
        $user = $this->reader->dispatch();

        self::assertEquals('jdoe@gmail.com', $user->getName());
    }
}
