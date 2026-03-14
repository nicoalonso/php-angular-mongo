<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorRepository;
use App\Tests\Fixtures\Mothers\AuthorMother;
use App\Tests\Fixtures\Ref;

/**
 * @template-extends EntityRepositoryStub<Author>
 */
final class AuthorRepositoryStub extends EntityRepositoryStub implements AuthorRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtainByName(string $name): ?Author
    {
        return $this->read;
    }

    protected function makeFixtures(): void
    {
        $shakespeare = AuthorMother::shakespeare();
        $this->addFixture(Ref::AuthorShakespeare, $shakespeare);

        $cervantes = AuthorMother::cervantes();
        $this->addFixture(Ref::AuthorCervantes, $cervantes);
    }
}
