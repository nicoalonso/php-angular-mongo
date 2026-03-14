<?php declare(strict_types=1);

namespace App\Presentation\V1\Author;

use App\Domain\Author\Author;
use App\Presentation\Identity\Result;

final class AuthorReadView extends Result
{
    public function __construct(Author $author)
    {
        parent::__construct($author);
    }

    /**
     * @param Author $data
     */
    public static function serialize(mixed $data): array
    {
        return [
            'id' => $data->getId(),
            'name' => $data->getName(),
            'realName' => $data->getRealName(),
            'genres' => $data->getGenres(),
            'biography' => $data->getBiography(),
            'nationality' => $data->getNationality(),
            'birthDate' => $data->getBirthDate()->format('Y-m-d'),
            'deathDate' => $data->getDeathDate()?->format('Y-m-d'),
            'photoUrl' => $data->getPhotoUrl(),
            'website' => $data->getWebsite(),
            'createdBy' => $data->getCreatedBy(),
            'createdAt' => $data->getCreatedAt()->format(DATE_ATOM),
            'updatedBy' => $data->getUpdatedBy(),
            'updatedAt' => $data->getUpdatedAt()?->format(DATE_ATOM),
        ];
    }
}
