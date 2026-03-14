<?php declare(strict_types=1);

namespace App\Application\Author\Creator;

use App\Domain\Identity\Payload;
use DateTimeImmutable;

class AuthorCreatePayload extends Payload
{
    private string $realName;
    private string $genres;
    private string $biography;
    private string $nationality;
    private DateTimeImmutable $birthDate;
    private ?DateTimeImmutable $deathDate;
    private string $photoUrl;
    private string $website;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->realName = $this->data->toString('realName');
        $this->genres = $this->data->toString('genres');
        $this->biography = $this->data->toString('biography');
        $this->nationality = $this->data->toString('nationality');
        $this->birthDate = $this->data->toDateImmutable('birthDate', DATE_SHORT, false);
        $this->deathDate = $this->data->toDateImmutable('deathDate', DATE_SHORT);
        $this->photoUrl = $this->data->toString('photoUrl');
        $this->website = $this->data->toString('website');
    }

    public function getRealName(): string
    {
        return $this->realName;
    }

    public function getGenres(): string
    {
        return $this->genres;
    }

    public function getBiography(): string
    {
        return $this->biography;
    }

    public function getNationality(): string
    {
        return $this->nationality;
    }

    public function getBirthDate(): DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function getDeathDate(): ?DateTimeImmutable
    {
        return $this->deathDate;
    }

    public function getPhotoUrl(): string
    {
        return $this->photoUrl;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }
}
