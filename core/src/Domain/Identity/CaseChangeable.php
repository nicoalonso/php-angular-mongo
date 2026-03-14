<?php

namespace App\Domain\Identity;

trait CaseChangeable
{
    protected function toCamelCase(string $key): string
    {
        $parts = explode('_', $key);
        $first = array_shift($parts);
        return $first . implode('', array_map(fn($item) => ucfirst($item), $parts));
    }

    protected function extractValue(object $object, string $propertyOrMethod): mixed
    {
        $value = null;
        $methods = [$propertyOrMethod];
        if (
            !str_starts_with('get', $propertyOrMethod) &&
            !str_starts_with('has', $propertyOrMethod) &&
            !str_starts_with('is', $propertyOrMethod)
        ) {
            $methods[] = $this->toCamelCase('get_' . $propertyOrMethod);
            $methods[] = $this->toCamelCase('has_' . $propertyOrMethod);
            $methods[] = $this->toCamelCase('is_' . $propertyOrMethod);
        }

        foreach ($methods as $method) {
            if (method_exists($object, $method)) {
                $value = $object->{$method}();
                break;
            }
        }

        return $value;
    }
}
