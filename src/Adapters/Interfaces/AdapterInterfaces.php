<?php

namespace Dg482\Mrd\Adapters\Interfaces;

use Closure;
use Dg482\Mrd\Model;

/**
 * Interface AdapterInterfaces
 * @package Dg482\Mrd\adapters\interfaces
 */
interface AdapterInterfaces
{
    /**
     * @param Model $model
     * @return AdapterInterfaces
     */
    public function setModel(Model $model): AdapterInterfaces;

    /**
     * @return Model
     */
    public function getModel(): Model;

    /**
     * @param  int  $limit
     * @return array
     */
    public function read($limit = 1): array;

    /**
     * @return bool
     */
    public function write(): bool;

    /**
     * @return bool
     */
    public function delete(): bool;

    /**
     * @return bool
     */
    public function update(): bool;

    /**
     * @return bool
     */
    public function execute(): bool;

    /**
     * @return bool
     */
    public function readCmd(): bool;

    /**
     * @param  Model  $model
     * @return array
     */
    public function getTableFields(Model $model): array;

    /**
     * @param  Closure|null  $filter
     * @return AdapterInterfaces
     */
    public function setFilter(?Closure $filter): AdapterInterfaces;
}
