<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Author\Author;
use App\Tests\Fixtures\Mothers\Base\BaseMother;
use App\Tests\Fixtures\Mothers\Base\MotherMapping;

final class AuthorMother extends BaseMother
{
    private const array SHAKESPEARE = [
        'name' => 'William Shakespeare',
        'realName' => 'William Shakespeare',
        'genres' => 'Tragedy, Comedy, History',
        'biography' => 'William Shakespeare was an English playwright, poet, and actor.',
        'nationality' => 'English',
        'birthDate' => ['1564-04-23', MotherMapping::DATE_IMMUTABLE],
        'deathDate' => ['1616-04-23', MotherMapping::DATE_IMMUTABLE],
        'photoUrl' => 'https://example.com/shakespeare.jpg',
        'website' => 'https://en.wikipedia.org/wiki/William_Shakespeare',
        'createdBy' => 'test',
    ];

    private const array CERVANTES = [
        'name' => 'Miguel de Cervantes',
        'realName' => 'Miguel de Cervantes Saavedra',
        'genres' => 'Novel, Drama, Poetry',
        'biography' => 'Miguel de Cervantes was a Spanish writer widely regarded as one of the greatest writers in the Spanish language.',
        'nationality' => 'Spanish',
        'birthDate' => ['1547-09-29', MotherMapping::DATE_IMMUTABLE],
        'deathDate' => ['1616-04-22', MotherMapping::DATE_IMMUTABLE],
        'photoUrl' => 'https://example.com/cervantes.jpg',
        'website' => 'https://en.wikipedia.org/wiki/Miguel_de_Cervantes',
        'createdBy' => 'test',
    ];

    public static function shakespeare(...$overrides): Author
    {
        return self::create(self::SHAKESPEARE, $overrides);
    }

    public static function cervantes(...$overrides): Author
    {
        return self::create(self::CERVANTES, $overrides);
    }

    protected static function create(array $values, array $overrides = []): Author
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new Author(
            $name,
            $realName,
            $genres,
            $biography,
            $nationality,
            $birthDate,
            $deathDate,
            $photoUrl,
            $website,
            $createdBy,
        );
    }
}
