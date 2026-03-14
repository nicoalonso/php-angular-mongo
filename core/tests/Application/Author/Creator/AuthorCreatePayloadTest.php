<?php

namespace App\Tests\Application\Author\Creator;

use App\Application\Author\Creator\AuthorCreatePayload;
use App\Tests\Fixtures\FixturePayload;
use PHPUnit\Framework\TestCase;

class AuthorCreatePayloadTest extends TestCase
{
    use FixturePayload;

    public function testShouldRunWhenCreate(): void
    {
        $data = $this->getPayload('author');
        $payload = new AuthorCreatePayload($data);

        self::assertEquals($data['name'], $payload->getRealName());
        self::assertEquals($data['realName'], $payload->getRealName());
        self::assertEquals($data['genres'], $payload->getGenres());
        self::assertEquals($data['biography'], $payload->getBiography());
        self::assertEquals($data['nationality'], $payload->getNationality());
        self::assertEquals($data['birthDate'], $payload->getBirthDate()->format(DATE_SHORT));
        self::assertEquals($data['deathDate'], $payload->getDeathDate()->format(DATE_SHORT));
        self::assertEquals($data['photoUrl'], $payload->getPhotoUrl());
        self::assertEquals($data['website'], $payload->getWebsite());
    }
}
