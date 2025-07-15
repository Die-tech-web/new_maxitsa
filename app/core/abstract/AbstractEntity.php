<?php
namespace App\Core\Abstract;
abstract class AbstractEntity
{
    abstract public static function toObject(array $data): static;
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
    abstract public function toArray(): array;
}