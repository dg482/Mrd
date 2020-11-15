<?php

namespace Dg482\Mrd\Builder\Form;

/**
 * Trait AttributeTrait
 * @package Dg482\Mrd\Builder\Form
 */
trait AttributeTrait
{

    /** @var array $attributes */
    protected array $attributes = [];


    /**
     * @param  bool  $set
     * @return void
     */
    public function showTable($set = true): void
    {
        $this->attributes['showTable'] = $set;
    }

    /**
     * @return bool
     */
    public function isShowTable(): bool
    {
        return isset($this->attributes['showTable']) ? true === $this->attributes['showTable'] : true;
    }

    /**
     * @param  bool  $set
     * @return void
     */
    public function showForm($set = true): void
    {
        $this->attributes['showForm'] = $set;
    }

    /**
     * @return bool
     */
    public function isShowForm(): bool
    {
        return isset($this->attributes['showForm']) ? true === $this->attributes['showForm'] : true;
    }
}
