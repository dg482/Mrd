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
    /** @var array */
    protected array $result;

    /** @var AdapterInterfaces */
    protected AdapterInterfaces $adapter;

    /** @var bool */
    protected bool $multiple = false;

    /**
     * Command constructor.
     */
    public function __construct()
    {
        $this->result = [];
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return (array)$this->result;
    }

    /**
     * @param $result
     * @return CommandInterfaces
     */
    public function setResult($result): CommandInterfaces
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
