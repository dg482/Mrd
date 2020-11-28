<?php

namespace Dg482\Mrd;

/**
 * Class BaseModel
 * @package Dg482\Mrd
 */
class BaseModel implements Model
{

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
