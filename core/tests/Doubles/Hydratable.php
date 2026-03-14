<?php

namespace App\Tests\Doubles;

use ReflectionException;
use ReflectionObject;

trait Hydratable
{
    public function hydrate(object $object, array $data): void
    {
        foreach ($data as $property => $value) {
            $this->hydrateProperty($object, $property, $value);
        }
    }

    public function hydrateProperty(object $object, string $property, mixed $value): void
    {
        try {
            $reflection = new ReflectionObject($object);
            $property = $reflection->getProperty($property);
            $property->setValue($object, $value);

        } catch (ReflectionException) {
            // Do nothing
        }
    }
}