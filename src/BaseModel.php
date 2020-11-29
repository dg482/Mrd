<?php

namespace Dg482\Mrd;

/**
 * Class BaseModel
 * @package Dg482\Mrd
 */
class BaseModel implements Model
{
    /** @var int */
    public int $id = 0;

    /** @var string */
    public string $name = '';

    public function update(array $request): bool
    {
        return false;
    }

    public function getFillableFields(): array
    {
        return [];
    }

    public function create(array $request): Model
    {
        return $this;
    }
}
