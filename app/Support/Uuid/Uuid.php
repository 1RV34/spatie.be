<?php

namespace App\Support\Uuid;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Livewire\Wireable;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid implements Castable, Wireable
{
    private function __construct(
        private string $uuid,
    ) {
    }

    public static function make(string $uuid): self
    {
        return new self($uuid);
    }

    public static function new(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public function __toString(): string
    {
        return $this->uuid;
    }

    public static function castUsing(array $arguments): UuidCaster
    {
        return new UuidCaster();
    }

    public function toLivewire()
    {
        return [$this->uuid];
    }

    public static function fromLivewire($value)
    {
        return new static($value[0]);
    }
}
