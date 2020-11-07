<?php

namespace Dg482\Mrd\Adapters;

use Closure;
use Dg482\Mrd\Adapters\Interfaces\AdapterInterfaces;
use Dg482\Mrd\Model;

/**
 * Class BaseAdapter
 * @package Dg482\Mrd\Adapters
 */
class BaseAdapter extends Adapter
{
    /** @var array */
    private array $fields = [];

    /** @var Model */
    private Model $model;

    /**
     * @param $model
     * @return $this|AdapterInterfaces
     */
    public function setModel($model): Interfaces\AdapterInterfaces
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return bool
     */
    public function readCmd(): bool
    {
        return false;
    }

    /**
     * @param  Model  $model
     * @return array
     */
    public function getTableFields(Model $model): array
    {
        return $this->fields;
    }

    /**
     * @param  Closure|null  $filter
     * @return $this|AdapterInterfaces
     */
    public function setFilter(?Closure $filter): AdapterInterfaces
    {
        return $this;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param  array  $fields
     * @return BaseAdapter
     */
    public function setFields(array $fields): BaseAdapter
    {
        $this->fields = $fields;

        return $this;
    }
}
