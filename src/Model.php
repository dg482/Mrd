<?php

namespace Dg482\Mrd;

/**
 * Interface Model
 * @package Dg482\Mrd
 */
interface Model
{
    /**
     * Обертка над методом обновления в модели
     * @param $request
     * @return bool
     */
    public function update(array $request): bool;

    /**
     * Получить поля авто заполнения
     * @return array
     */
    public function getFillableFields(): array;

    /**
     * Обертка над методом создания модели
     * @param  array  $request
     * @return Model
     */
    public function create(array $request): Model;
}
