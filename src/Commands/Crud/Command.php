<?php

namespace Dg482\Mrd\Commands\Crud;

use Dg482\Mrd\Adapters\Interfaces\AdapterInterfaces;
use Dg482\Mrd\Commands\Interfaces\CommandInterfaces;

/**
 * Class Command
 * @package Dg482\Mrd\Commands\Crud
 */
abstract class Command implements CommandInterfaces
{
    /** @var array|bool */
    protected $result;

    /** @var AdapterInterfaces */
    protected AdapterInterfaces $adapter;

    /** @var bool */
    protected bool $multiple = false;

    /**
     * @return array|bool
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param $result
     * @return $this
     */
    public function setResult($result): self
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * @param  bool  $multiple
     * @return $this
     */
    public function setMultiple(bool $multiple): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * @return AdapterInterfaces
     */
    public function getAdapter(): AdapterInterfaces
    {
        return $this->adapter;
    }

    /**
     * @param  AdapterInterfaces  $adapter
     * @return $this
     */
    public function setAdapter(AdapterInterfaces $adapter): self
    {
        $this->adapter = $adapter;

        return $this;
    }
}
