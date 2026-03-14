<?php declare(strict_types=1);

namespace App\Domain\Author;

use App\Domain\Author\Exception\InvalidBirthDateException;
use App\Domain\Author\Exception\InvalidDeathDateException;
use App\Domain\Identity\Entity;
use App\Domain\Identity\Exception\NameEmptyException;
use DateTimeImmutable;

class Author extends Entity
{
    private string $name;
    private string $realName;
    private string $genres;
    private string $biography;
    private string $nationality;
    private DateTimeImmutable $birthDate;
    private ?DateTimeImmutable $deathDate;
    private string $photoUrl;
    private string $website;

    public function __construct(
        string             $name,
        string             $realName,
        string             $genres,
        string             $biography,
        string             $nationality,
        DateTimeImmutable  $birthDate,
        ?DateTimeImmutable $deathDate,
        string             $photoUrl,
        string             $website,
        string             $createdBy,
    )
    {
        parent::__construct($createdBy);

        $this->check($name, $birthDate, $deathDate);

        $this->name = $name;
        $this->realName = $realName;
        $this->genres = $genres;
        $this->biography = $biography;
        $this->nationality = $nationality;
        $this->birthDate = $birthDate;
        $this->deathDate = $deathDate;
        $this->photoUrl = $photoUrl;
        $this->website = $website;
    }

    public function modify(
        string $name,
        string $stageName,
        string $genres,
        string $biography,
        string $nationality,
        DateTimeImmutable $birthDate,
        ?DateTimeImmutable $deathDate,
        string $photoUrl,
        string $website,
        string $updatedBy,
    ): void
    {
        $this->check($name, $birthDate, $deathDate);

        $this->name = $name;
        $this->realName = $stageName;
        $this->genres = $genres;
        $this->biography = $biography;
        $this->nationality = $nationality;
        $this->photoUrl = $photoUrl;
        $this->website = $website;
        $this->birthDate = $birthDate;
        $this->deathDate = $deathDate;

        $this->updated($updatedBy);
    }

    private function check(string $name, DateTimeImmutable $birthDate, ?DateTimeImmutable $deathDate): void
    {
        if (empty($name)) {
            throw new NameEmptyException();
        }

        $now = new DateTimeImmutable('today midnight');
        if ($birthDate > $now) {
            throw new InvalidBirthDateException('Birth date cannot be in the future.');
        }

        $now = $now->modify('+1 day');
        if (null !== $deathDate) {
            if ($deathDate > $now) {
                throw new InvalidDeathDateException('Death date cannot be in the future.');
            }
            if ($deathDate < $birthDate) {
                throw new InvalidDeathDateException('Death date cannot be before birth date.');
            }
        }
    }

    public function getDescriptor(): AuthorDescriptor
    {
        return new AuthorDescriptor($this->getId(), $this->name);
    }

    public function getName(): string
    {
        return $this->name;
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
