<?php declare(strict_types=1);

namespace App\Application\Author\Updater;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorRepository;
use App\Domain\Author\Exception\AuthorNotFoundException;
use App\Domain\User\UserRepository;

final readonly class AuthorUpdate
{
    public function __construct(
        private AuthorRepository $repoAuthor,
        private UserRepository $repoUser,
    ) {}

    public function dispatch(string $authorId, AuthorUpdatePayload $payload): Author
    {
        $author = $this->repoAuthor->obtainById($authorId);
        if (null === $author) {
            throw new AuthorNotFoundException();
        }

        $user = $this->repoUser->obtainUser();
        $author->modify(
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
}
