<?php

namespace App\Tests\Fixtures;

trait FixtureLoadable
{
    protected string $fixtureSection;
    protected ?string $fixtureType;

    protected function fixture(string $section, ?string $type = null): void
    {
        $this->fixtureSection = $section;
        $this->fixtureType = $type;
    }

    protected function load(string $file, ?string $type = null): string
    {
        $extension = '';
        if (null === $type) {
            $type = $this->fixtureType;
        }
        if (null !== $type) {
            $extension = ".$type";
        }

        $filepath = sprintf(
            '%s/%s/%s%s',
            __DIR__,
            $this->fixtureSection,
            $file,
            $extension
        );

        if (!file_exists($filepath)) {
            throw new FixtureNotFoundException();
        }

        return file_get_contents($filepath);
    }
}
