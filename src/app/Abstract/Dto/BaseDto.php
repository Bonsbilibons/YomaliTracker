<?php

namespace App\Abstract\Dto;

abstract class BaseDto
{
    abstract public static function fromArray(array $params): self;

    public function toArray(): array
    {
        return $this->convertToSnakeCase(get_object_vars($this));
    }

    public function get(string $property): mixed
    {
        return $this->{$property} ?? $this->toArray()[$property] ?? null;
    }

    private function convertToSnakeCase(array|object $data) {
        $preparedData = [];
        foreach ($data as $property => $value) {
            $preparedData[$this->toSnakeCase($property)] = $value;
        }

        return $preparedData;
    }

    private function toSnakeCase(string $value): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $value));
    }
}
