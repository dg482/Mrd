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
    /** @var array $fields */
    private array $fields;

    /** @var Model */
    private Model $model;

    /**
     * BaseAdapter constructor.
     * @param  Model  $model
     * @param  array  $fields
     */
    public function __construct(Model $model, array $fields = [])
    {
        $this->model = $model;
        $this->fields = $fields;
    }

    /**
     * @param  Model  $model
     * @return AdapterInterfaces
     */
    public function setModel(Model $model): AdapterInterfaces
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
        $this->getCommand()->setResult([]);

        return true;
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
