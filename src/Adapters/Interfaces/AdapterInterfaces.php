<?php

namespace Dg482\Mrd\Adapters\Interfaces;

use Closure;
use Dg482\Mrd\Model;
use IteratorAggregate;

/**
 * Interface AdapterInterfaces
 * @package Dg482\Mrd\adapters\interfaces
 */
interface AdapterInterfaces
{
    /**
     * @param $model
     * @return $this
     */
    public function setModel($model): self;

    /**
     * @return Model
     */
    public function getModel(): Model;

    /**
     * @param  int  $limit
     * @return array|bool
     */
    public function read($limit = 1);

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
