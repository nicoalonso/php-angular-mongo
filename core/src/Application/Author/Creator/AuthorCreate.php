<?php declare(strict_types=1);

namespace App\Application\Author\Creator;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorRepository;
use App\Domain\Author\Exception\AuthorAlreadyExistException;
use App\Domain\User\UserRepository;

final readonly class AuthorCreate
{
    public function __construct(
        private AuthorRepository $repoAuthor,
        private UserRepository $repoUser,
    ) {}

    public function dispatch(AuthorCreatePayload $payload): Author
    {
        $this->checkAlreadyExists($payload);

        $user = $this->repoUser->obtainUser();
        $author = new Author(
            $payload->getName(),
            $payload->getRealName(),
            $payload->getGenres(),
            $payload->getBiography(),
            $payload->getNationality(),
            $payload->getBirthDate(),
            $payload->getDeathDate(),
            $payload->getPhotoUrl(),
            $payload->getWebsite(),
            $user->getName(),
        );

        $this->repoAuthor->save($author);
        return $author;
    }

    private function checkAlreadyExists(AuthorCreatePayload $payload): void
    {
        $author = $this->repoAuthor->obtainByName($payload->getName());
        if ($author) {
            throw new AuthorAlreadyExistException();
        }
    }
}
